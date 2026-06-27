<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Stock;
use App\Models\Kandang;
use App\Models\EggSize;
use App\Models\DailyPrice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');

        // Ringkasan produksi hari ini
        $produksiHariIni = Production::where('tanggal', $today)->count();

        // Total telur sortir hari ini (dari production_details)
        $sortirHariIni = ProductionDetail::whereHas('production', fn($q) => $q->where('tanggal', $today))
            ->sum('jumlah_butir');

        // Total pendapatan hari ini
        $pendapatanHariIni = ProductionDetail::whereHas('production', fn($q) => $q->where('tanggal', $today))
            ->sum('subtotal');

        // Total stok gudang (semua ukuran)
        $totalStok = Stock::sum('jumlah_butir');

        // Jumlah kandang aktif
        $totalKandang = Kandang::where('status', 'Active')->count();

        // Jumlah ukuran telur aktif
        $totalUkuran = EggSize::where('status', 'Active')->count();

        // Stok per ukuran
        $stocks = Stock::with('eggSize')->get();

        // Produksi 7 hari terakhir (untuk chart)
        $last7days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $butir = ProductionDetail::whereHas('production', fn($q) => $q->where('tanggal', $tgl))
                ->sum('jumlah_butir');
            $last7days->push([
                'tanggal' => now()->subDays($i)->format('d M'),
                'butir'   => (int) $butir,
            ]);
        }

        // Harga terbaru per ukuran
        $hargaTerkini = DailyPrice::with('eggSize')
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')->from('daily_prices')->groupBy('egg_size_id');
            })
            ->orderBy('egg_size_id')
            ->get();

        // Produksi terbaru (5 data)
        $produksiTerbaru = Production::with(['kandang', 'details.eggSize'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'produksiHariIni',
            'sortirHariIni',
            'pendapatanHariIni',
            'totalStok',
            'totalKandang',
            'totalUkuran',
            'stocks',
            'last7days',
            'hargaTerkini',
            'produksiTerbaru',
            'today'
        ));
    }
}
