<?php

namespace App\Http\Controllers;

use App\Models\DailyPrice;
use App\Models\EggCategory;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Stock;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['creator', 'details.eggCategory'])->orderBy('tanggal', 'desc')->get();
        return view('admin.sales.index', compact('sales'));
    }

    public function create(Request $request)
    {
        $categories = EggCategory::where('status', 'Active')
            ->where('unit_penjualan', '!=', 'tidak')
            ->orderBy('urutan')
            ->get();
        $stocks = Stock::with('eggCategory')->get()->keyBy('egg_category_id');
        $settings = SystemSetting::first();
        $butirPerPapan = $settings->butir_per_papan ?? 30;
        $papanPerIkat = $settings->papan_per_ikat ?? 5;

        $selectedTanggal = $request->tanggal ?? now()->format('Y-m-d');

        // Get active prices for the selected date
        $activePrice = DailyPrice::where('tanggal_berlaku', '<=', $selectedTanggal)
            ->orderBy('tanggal_berlaku', 'desc')
            ->first();

        $hargaMap = [];
        if ($activePrice) {
            $hargaMap = [
                'J' => $activePrice->jumbo,
                'B' => $activePrice->besar,
                'S' => $activePrice->sedang,
                'K' => $activePrice->kecil,
                'P' => $activePrice->putih,
            ];
        }

        $lastInvoice = Sale::whereYear('created_at', now()->year)
            ->orderBy('id', 'desc')
            ->value('nomor_invoice');

        $nextNumber = $lastInvoice ? ((int)filter_var($lastInvoice, FILTER_SANITIZE_NUMBER_INT) + 1) : 1;
        $nomorInvoice = 'INV-' . now()->format('Ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.sales.create', compact(
            'categories', 'stocks', 'selectedTanggal',
            'hargaMap', 'nomorInvoice', 'butirPerPapan', 'papanPerIkat'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nomor_invoice' => 'required|string|max:50',
            'customer' => 'nullable|string|max:200',
            'catatan' => 'nullable|string|max:500',
            'details' => 'required|array|min:1',
            'details.*.egg_category_id' => 'required|exists:egg_categories,id',
            'details.*.ikat' => 'nullable|integer|min:0',
            'details.*.papan' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $sale = Sale::create([
                'tanggal' => $validated['tanggal'],
                'nomor_invoice' => $validated['nomor_invoice'],
                'customer' => $validated['customer'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $activePrice = DailyPrice::where('tanggal_berlaku', '<=', $validated['tanggal'])
                ->orderBy('tanggal_berlaku', 'desc')
                ->first();

            $settings = SystemSetting::first();
            $butirPerPapan = $settings->butir_per_papan ?? 30;

            foreach ($validated['details'] as $detail) {
                $inIkat = (int)($detail['ikat'] ?? 0);
                $inPapan = (int)($detail['papan'] ?? 0);

                if ($inIkat <= 0 && $inPapan <= 0) {
                    continue;
                }

                $category = EggCategory::findOrFail($detail['egg_category_id']);

                // Get price from active price
                $hargaPerButir = 0;
                if ($activePrice) {
                    $kodeField = strtolower($category->kode);
                    $fieldMap = ['j' => 'jumbo', 'b' => 'besar', 's' => 'sedang', 'k' => 'kecil', 'p' => 'putih'];
                    $field = $fieldMap[$kodeField] ?? null;
                    if ($field) {
                        $hargaPerButir = $activePrice->$field ?? 0;
                    }
                }

                // Calculate total butir
                $totalButir = 0;
                if ($category->unit_penjualan === 'papan') {
                    $totalButir = $inPapan * $butirPerPapan;
                } elseif ($category->unit_penjualan === 'ikat') {
                    $totalButir = $inIkat * $butirPerPapan * 5; // ikat = 5 papan * butir_per_papan
                }

                $subtotal = $totalButir * $hargaPerButir;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'egg_category_id' => $detail['egg_category_id'],
                    'ikat' => $inIkat,
                    'papan' => $inPapan,
                    'harga_per_butir' => $hargaPerButir,
                    'subtotal' => $subtotal,
                ]);

                // Reduce stock
                $stock = Stock::where('egg_category_id', $detail['egg_category_id'])->first();
                if ($stock) {
                    $stock->decrement('ikat', $inIkat);
                    $stock->decrement('papan', $inPapan);
                    $stock->touch();
                }
            }
        });

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil disimpan.');
    }

    public function destroy($id)
    {
        $sale = Sale::with('details')->findOrFail($id);

        DB::transaction(function () use ($sale) {
            foreach ($sale->details as $detail) {
                $stock = Stock::where('egg_category_id', $detail->egg_category_id)->first();
                if ($stock) {
                    $stock->increment('ikat', $detail->ikat);
                    $stock->increment('papan', $detail->papan);
                    $stock->touch();
                }
            }
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus, stok dikembalikan.');
    }
}
