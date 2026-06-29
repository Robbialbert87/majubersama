<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use App\Models\ContractSale;
use App\Models\DailyPrice;
use App\Models\EggCategory;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Stock;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $productions = Production::with(['barn', 'creator', 'items.eggCategory'])
            ->whereDate('tanggal', $tanggal)
            ->orderBy('barn_id')
            ->get();

        return view('admin.productions.index', compact('productions', 'tanggal'));
    }

    public function create(Request $request)
    {
        $barns = Barn::where('status', 'Active')->orderBy('kode')->get();
        $categories = EggCategory::where('status', 'Active')->orderBy('urutan')->get();
        $settings = SystemSetting::first();
        $butirPerPapan = $settings->butir_per_papan ?? 30;
        $papanPerIkat = $settings->papan_per_ikat ?? 5;

        $selectedTanggal = $request->tanggal ?? now()->format('Y-m-d');
        $selectedBarn = $request->barn_id ?? null;

        $existingItems = collect();
        $existingPecah = null;
        $existingCatatan = null;
        if ($selectedBarn) {
            $prod = Production::whereDate('tanggal', $selectedTanggal)->where('barn_id', $selectedBarn)->first();
            if ($prod) {
                $existingItems = $prod->items->keyBy('egg_category_id');
                $existingPecah = $prod->pecah;
                $existingCatatan = $prod->catatan;
            }
        }

        return view('admin.productions.create', compact(
            'barns', 'categories', 'selectedTanggal', 'selectedBarn',
            'existingItems', 'butirPerPapan', 'papanPerIkat', 'existingPecah', 'existingCatatan'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'barn_id' => 'required|exists:barns,id',
            'catatan' => 'nullable|string|max:500',
            'pecah' => 'nullable|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.egg_category_id' => 'required|exists:egg_categories,id',
            'items.*.ikat' => 'nullable|integer|min:0',
            'items.*.papan' => 'nullable|integer|min:0',
            'items.*.sisa_butir' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $production = Production::firstOrCreate(
                ['tanggal' => $validated['tanggal'], 'barn_id' => $validated['barn_id']],
                ['catatan' => $validated['catatan'] ?? null, 'pecah' => $validated['pecah'] ?? 0, 'created_by' => auth()->id()]
            );

            if (!$production->wasRecentlyCreated) {
                $production->update([
                    'catatan' => $validated['catatan'] ?? null,
                    'pecah' => $validated['pecah'] ?? 0,
                ]);
            }

            $settings = SystemSetting::first();
            $butirPerPapan = $settings->butir_per_papan ?? 30;
            $papanPerIkat = $settings->papan_per_ikat ?? 5;

            $activePrice = DailyPrice::where('tanggal_berlaku', '<=', $validated['tanggal'])
                ->orderBy('tanggal_berlaku', 'desc')
                ->first();

            foreach ($validated['items'] as $item) {
                $inIkat = (int)($item['ikat'] ?? 0);
                $inPapan = (int)($item['papan'] ?? 0);
                $inSisa = (int)($item['sisa_butir'] ?? 0);

                if ($inIkat <= 0 && $inPapan <= 0 && $inSisa <= 0) {
                    continue;
                }

                $category = EggCategory::findOrFail($item['egg_category_id']);

                $existing = ProductionItem::where('production_id', $production->id)
                    ->where('egg_category_id', $item['egg_category_id'])
                    ->first();

                if ($existing) {
                    $stock = Stock::where('egg_category_id', $item['egg_category_id'])->first();
                    if ($stock) {
                        if ($category->unit_penjualan === 'ikat') {
                            $stock->decrement('papan', $existing->papan);
                            $stock->decrement('sisa_butir', $existing->sisa_butir);
                        } else {
                            $stock->decrement('ikat', $existing->ikat);
                            $stock->decrement('papan', $existing->papan);
                            $stock->decrement('sisa_butir', $existing->sisa_butir);
                        }
                    }

                    ContractSale::where('production_item_id', $existing->id)->delete();

                    $existing->update([
                        'ikat' => $inIkat,
                        'papan' => $inPapan,
                        'sisa_butir' => $inSisa,
                    ]);
                } else {
                    $existing = ProductionItem::create([
                        'production_id' => $production->id,
                        'egg_category_id' => $item['egg_category_id'],
                        'ikat' => $inIkat,
                        'papan' => $inPapan,
                        'sisa_butir' => $inSisa,
                    ]);
                }

                // Auto-create contract sale for ikat categories
                if ($category->unit_penjualan === 'ikat' && $inIkat > 0) {
                    $hargaPerButir = 0;
                    if ($activePrice) {
                        $kodeField = strtolower($category->kode);
                        $fieldMap = ['j' => 'jumbo', 'b' => 'besar', 's' => 'sedang', 'k' => 'kecil', 'p' => 'putih'];
                        $field = $fieldMap[$kodeField] ?? null;
                        if ($field) {
                            $hargaPerButir = $activePrice->$field ?? 0;
                        }
                    }

                    $totalButir = $inIkat * $papanPerIkat * $butirPerPapan;
                    $totalPenjualan = $totalButir * $hargaPerButir;

                    ContractSale::create([
                        'tanggal' => $validated['tanggal'],
                        'barn_id' => $validated['barn_id'],
                        'egg_category_id' => $item['egg_category_id'],
                        'production_item_id' => $existing->id,
                        'jumlah_ikat' => $inIkat,
                        'harga_per_butir' => $hargaPerButir,
                        'total_butir' => $totalButir,
                        'total_penjualan' => $totalPenjualan,
                    ]);
                }

                // Update stock
                $stockIkat = ($category->unit_penjualan === 'papan') ? $inIkat : 0;
                $stockPapan = $inPapan;
                $stockSisa = $inSisa;

                Stock::updateOrCreate(
                    ['egg_category_id' => $item['egg_category_id']],
                    [
                        'ikat' => DB::raw("ikat + {$stockIkat}"),
                        'papan' => DB::raw("papan + {$stockPapan}"),
                        'sisa_butir' => DB::raw("sisa_butir + {$stockSisa}"),
                        'updated_at' => now(),
                    ]
                );
            }
        });

        return redirect()->route('productions.index')->with('success', 'Produksi berhasil disimpan. Kontrak penjualan dan stok diperbarui.');
    }

    public function destroy($id)
    {
        $production = Production::with('items')->findOrFail($id);

        DB::transaction(function () use ($production) {
            foreach ($production->items as $item) {
                $category = $item->eggCategory;

                ContractSale::where('production_item_id', $item->id)->delete();

                $stock = Stock::where('egg_category_id', $item->egg_category_id)->first();
                if ($stock) {
                    if ($category->unit_penjualan === 'ikat') {
                        $stock->decrement('papan', $item->papan);
                        $stock->decrement('sisa_butir', $item->sisa_butir);
                    } else {
                        $stock->decrement('ikat', $item->ikat);
                        $stock->decrement('papan', $item->papan);
                        $stock->decrement('sisa_butir', $item->sisa_butir);
                    }
                }
            }
            $production->delete();
        });

        return redirect()->route('productions.index')->with('success', 'Produksi berhasil dihapus, stok & kontrak disesuaikan.');
    }
}
