<?php

namespace App\Http\Controllers;

use App\Models\ContractSale;
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

        $groupedByBarn = $productions->groupBy('barn_id');
        $totalItems = ProductionItem::whereHas('production', fn($q) => $q->where('tanggal', $tanggal))->get();
        $grandTotal = [
            'ikat' => $totalItems->sum('ikat'),
            'papan' => $totalItems->sum('papan'),
            'sisa_butir' => $totalItems->sum('sisa_butir'),
        ];

        return view('admin.laporan.produksi-harian', compact('groupedByBarn', 'productions', 'tanggal', 'grandTotal'));
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

    public function penjualanKontrak(Request $request)
    {
        $start = $request->start ?? now()->format('Y-m-d');
        $end = $request->end ?? now()->format('Y-m-d');

        $contractSales = ContractSale::with(['barn', 'eggCategory'])
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->orderBy('barn_id')
            ->orderBy('egg_category_id')
            ->get();

        $groupedByBarn = $contractSales->groupBy('barn_id');
        $totalPenjualan = $contractSales->sum('total_penjualan');

        return view('admin.laporan.penjualan-kontrak', compact('groupedByBarn', 'contractSales', 'start', 'end', 'totalPenjualan'));
    }

    public function stockGudang()
    {
        $categories = EggCategory::orderBy('urutan')->get();
        $stocks = Stock::with('eggCategory')->get()->keyBy('egg_category_id');
        $settings = SystemSetting::first();
        $butirPerPapan = $settings->butir_per_papan ?? 30;

        $productions = Production::with(['barn', 'items.eggCategory'])->get();
        $groupedByBarn = $productions->groupBy('barn_id');

        $barnStock = collect();
        foreach ($groupedByBarn as $barnId => $prods) {
            $barn = $prods->first()->barn;
            $items = [];
            foreach ($categories as $cat) {
                $items[$cat->id] = ['ikat' => 0, 'papan' => 0, 'sisa_butir' => 0];
            }
            foreach ($prods as $p) {
                foreach ($p->items as $item) {
                    $cat = $item->eggCategory;
                    if ($cat->unit_penjualan === 'papan') {
                        $items[$item->egg_category_id]['ikat'] += $item->ikat;
                        $items[$item->egg_category_id]['papan'] += $item->papan;
                        $items[$item->egg_category_id]['sisa_butir'] += $item->sisa_butir;
                    } else {
                        $items[$item->egg_category_id]['papan'] += $item->papan;
                        $items[$item->egg_category_id]['sisa_butir'] += $item->sisa_butir;
                    }
                }
            }
            $barnStock->push(['barn' => $barn, 'items' => $items]);
        }

        return view('admin.laporan.stock-gudang', compact('categories', 'stocks', 'butirPerPapan', 'barnStock'));
    }

    public function telurPecah(Request $request)
    {
        $start = $request->start ?? now()->startOfMonth()->format('Y-m-d');
        $end = $request->end ?? now()->format('Y-m-d');

        $productions = Production::with('barn')
            ->where('pecah', '>', 0)
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->orderBy('barn_id')
            ->get();

        $items = $productions->map(fn($p) => [
            'tanggal' => $p->tanggal,
            'barn_kode' => $p->barn->kode ?? '-',
            'barn_nama' => $p->barn->nama ?? '-',
            'jumlah_butir' => $p->pecah,
        ]);

        $dailyTotals = $items->groupBy(fn($i) => $i['tanggal']->format('Y-m-d'))->map(fn($day) => [
            'tanggal' => $day->first()['tanggal'],
            'jumlah_butir' => $day->sum('jumlah_butir'),
        ])->values();

        $weeklyTotals = $items->groupBy(fn($i) => $i['tanggal']->format('o-W'))->map(fn($week) => [
            'week_label' => 'Minggu ke-' . $week->first()['tanggal']->format('W') . ' (' . $week->first()['tanggal']->startOfWeek()->format('d/m') . '-' . $week->first()['tanggal']->copy()->endOfWeek()->format('d/m') . ')',
            'jumlah_butir' => $week->sum('jumlah_butir'),
        ])->values();

        $monthlyTotals = $items->groupBy(fn($i) => $i['tanggal']->format('Y-m'))->map(fn($month) => [
            'month_label' => $month->first()['tanggal']->isoFormat('MMMM Y'),
            'jumlah_butir' => $month->sum('jumlah_butir'),
        ])->values();

        $grandTotal = [
            'jumlah_butir' => $items->sum('jumlah_butir'),
        ];

        return view('admin.laporan.telur-pecah', compact('items', 'dailyTotals', 'weeklyTotals', 'monthlyTotals', 'grandTotal', 'start', 'end'));
    }
}
