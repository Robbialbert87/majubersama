<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use App\Models\EggCategory;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Stock;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with(['barn', 'creator', 'items.eggCategory'])->orderBy('tanggal', 'desc')->orderBy('barn_id')->get();
        return view('admin.productions.index', compact('productions'));
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

        // Pre-fill existing items if editing a production for same date+barn
        $existingItems = collect();
        if ($selectedBarn) {
            $prod = Production::whereDate('tanggal', $selectedTanggal)->where('barn_id', $selectedBarn)->first();
            if ($prod) {
                $existingItems = $prod->items->keyBy('egg_category_id');
            }
        }

        return view('admin.productions.create', compact(
            'barns', 'categories', 'selectedTanggal', 'selectedBarn',
            'existingItems', 'butirPerPapan', 'papanPerIkat'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'barn_id' => 'required|exists:barns,id',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.egg_category_id' => 'required|exists:egg_categories,id',
            'items.*.ikat' => 'nullable|integer|min:0',
            'items.*.papan' => 'nullable|integer|min:0',
            'items.*.sisa_butir' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $production = Production::firstOrCreate(
                ['tanggal' => $validated['tanggal'], 'barn_id' => $validated['barn_id']],
                ['catatan' => $validated['catatan'] ?? null, 'created_by' => auth()->id()]
            );

            foreach ($validated['items'] as $item) {
                $inIkat = (int)($item['ikat'] ?? 0);
                $inPapan = (int)($item['papan'] ?? 0);
                $inSisa = (int)($item['sisa_butir'] ?? 0);

                if ($inIkat <= 0 && $inPapan <= 0 && $inSisa <= 0) {
                    continue;
                }

                $existing = ProductionItem::where('production_id', $production->id)
                    ->where('egg_category_id', $item['egg_category_id'])
                    ->first();

                if ($existing) {
                    // Revert old stock first
                    $stock = Stock::where('egg_category_id', $item['egg_category_id'])->first();
                    if ($stock) {
                        $stock->decrement('ikat', $existing->ikat);
                        $stock->decrement('papan', $existing->papan);
                        $stock->decrement('sisa_butir', $existing->sisa_butir);
                    }

                    $existing->update([
                        'ikat' => $inIkat,
                        'papan' => $inPapan,
                        'sisa_butir' => $inSisa,
                    ]);
                } else {
                    ProductionItem::create([
                        'production_id' => $production->id,
                        'egg_category_id' => $item['egg_category_id'],
                        'ikat' => $inIkat,
                        'papan' => $inPapan,
                        'sisa_butir' => $inSisa,
                    ]);
                }

                // Update stock
                Stock::updateOrCreate(
                    ['egg_category_id' => $item['egg_category_id']],
                    [
                        'ikat' => DB::raw("ikat + {$inIkat}"),
                        'papan' => DB::raw("papan + {$inPapan}"),
                        'sisa_butir' => DB::raw("sisa_butir + {$inSisa}"),
                        'updated_at' => now(),
                    ]
                );
            }
        });

        return redirect()->route('productions.index')->with('success', 'Produksi berhasil disimpan dan stok diperbarui.');
    }

    public function destroy($id)
    {
        $production = Production::with('items')->findOrFail($id);

        DB::transaction(function () use ($production) {
            foreach ($production->items as $item) {
                $stock = Stock::where('egg_category_id', $item->egg_category_id)->first();
                if ($stock) {
                    $stock->decrement('ikat', $item->ikat);
                    $stock->decrement('papan', $item->papan);
                    $stock->decrement('sisa_butir', $item->sisa_butir);
                }
            }
            $production->delete();
        });

        return redirect()->route('productions.index')->with('success', 'Produksi berhasil dihapus, stok disesuaikan.');
    }
}
