<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use App\Models\DailyPrice;
use App\Models\EggCategory;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Stock;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');

        $settings = SystemSetting::first();
        $butirPerPapan = $settings->butir_per_papan ?? 30;
        $papanPerIkat = $settings->papan_per_ikat ?? 5;

        // Production today
        $todayItems = ProductionItem::whereHas('production', fn($q) => $q->where('tanggal', $today))->get();
        $produksiHariIni = $todayItems->sum(fn($i) => ($i->ikat * $papanPerIkat * $butirPerPapan) + ($i->papan * $butirPerPapan) + $i->sisa_butir);

        // Total stock in butir
        $totalStok = 0;
        $stocks = Stock::with('eggCategory')->get();

        foreach ($stocks as $s) {
            $totalStok += ($s->ikat * $papanPerIkat * $butirPerPapan) + ($s->papan * $butirPerPapan) + $s->sisa_butir;
        }

        // Active barns count
        $totalKandang = Barn::where('status', 'Active')->count();
        $totalUkuran = EggCategory::where('status', 'Active')->count();

        // Last 7 days production
        $last7days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $butir = ProductionItem::whereHas('production', fn($q) => $q->where('tanggal', $date))
                ->sum(DB::raw('(ikat * ' . $papanPerIkat . ' * ' . $butirPerPapan . ') + (papan * ' . $butirPerPapan . ') + sisa_butir'));
            $last7days->push([
                'tanggal' => now()->subDays($i)->isoFormat('dd'),
                'date' => $date,
                'butir' => $butir,
            ]);
        }

        // Current prices
        $activePrice = DailyPrice::where('tanggal_berlaku', '<=', $today)
            ->orderBy('tanggal_berlaku', 'desc')
            ->first();

        // Recent productions
        $produksiTerbaru = Production::with(['barn', 'items.eggCategory'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Stock per category
        $categoryStocks = Stock::with('eggCategory')->get();

        return view('admin.dashboard', compact(
            'today', 'produksiHariIni', 'totalStok', 'totalKandang', 'totalUkuran',
            'last7days', 'activePrice', 'produksiTerbaru', 'categoryStocks',
            'butirPerPapan', 'papanPerIkat'
        ));
    }
}
