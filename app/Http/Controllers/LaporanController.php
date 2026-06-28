<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\DailyPrice;
use App\Models\Barn;
use App\Models\EggCategory;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function produksiHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $productions = Production::with(['barn', 'items.eggCategory'])
            ->where('tanggal', $tanggal)
            ->orderBy('barn_id')
            ->get();
        $totalItems = ProductionItem::whereHas('production', fn($q) => $q->where('tanggal', $tanggal))->get();
        $grandTotal = [
            'ikat' => $totalItems->sum('ikat'),
            'papan' => $totalItems->sum('papan'),
            'sisa_butir' => $totalItems->sum('sisa_butir'),
        ];

        return view('admin.laporan.produksi-harian', compact('productions', 'tanggal', 'grandTotal'));
    }

    public function produksiMingguan(Request $request)
    {
        $start = $request->start ?? now()->startOfWeek()->format('Y-m-d');
        $end = $request->end ?? now()->format('Y-m-d');
        $productions = Production::with(['barn', 'items.eggCategory'])
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal', 'desc')
            ->orderBy('barn_id')
            ->get();

        return view('admin.laporan.produksi-mingguan', compact('productions', 'start', 'end'));
    }

    public function produksiBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        [$year, $month] = explode('-', $bulan);
        $productions = Production::with(['barn', 'items.eggCategory'])
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal', 'desc')
            ->orderBy('barn_id')
            ->get();

        return view('admin.laporan.produksi-bulanan', compact('productions', 'bulan', 'year', 'month'));
    }

    public function penjualan(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $sales = Sale::with(['creator', 'details.eggCategory'])
            ->where('tanggal', $tanggal)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPenjualan = $sales->sum(function ($s) {
            return $s->details->sum('subtotal');
        });

        return view('admin.laporan.penjualan', compact('sales', 'tanggal', 'totalPenjualan'));
    }

    public function stockGudang()
    {
        $categories = EggCategory::orderBy('urutan')->get();
        $stocks = Stock::with('eggCategory')->get()->keyBy('egg_category_id');
        $settings = SystemSetting::first();
        $butirPerPapan = $settings->butir_per_papan ?? 30;

        return view('admin.laporan.stock-gudang', compact('categories', 'stocks', 'butirPerPapan'));
    }
}
