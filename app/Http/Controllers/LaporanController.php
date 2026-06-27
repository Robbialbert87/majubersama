<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\DailyPrice;
use App\Models\Kandang;
use App\Models\EggSize;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function produksiHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $productions = Production::with(['kandang', 'details.eggSize'])->where('tanggal', $tanggal)->orderBy('kandang_id')->get();
        $groups = $productions->groupBy(fn($p) => $p->kandang->nama ?? $p->kandang->kode ?? 'Lainnya');
        return view('admin.laporan.produksi-harian', compact('groups', 'tanggal'));
    }

    public function produksiMingguan(Request $request)
    {
        $start = $request->start ?? now()->startOfWeek()->format('Y-m-d');
        $end = $request->end ?? now()->format('Y-m-d');
        $productions = Production::with(['kandang', 'details.eggSize'])->whereBetween('tanggal', [$start, $end])->orderBy('kandang_id')->orderBy('tanggal', 'desc')->get();
        $groups = $productions->groupBy(fn($p) => $p->kandang->nama ?? $p->kandang->kode ?? 'Lainnya');
        return view('admin.laporan.produksi-mingguan', compact('groups', 'start', 'end'));
    }

    public function produksiBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        [$year, $month] = explode('-', $bulan);
        $productions = Production::with(['kandang', 'details.eggSize'])->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->orderBy('kandang_id')->orderBy('tanggal', 'desc')->get();
        $groups = $productions->groupBy(fn($p) => $p->kandang->nama ?? $p->kandang->kode ?? 'Lainnya');
        return view('admin.laporan.produksi-bulanan', compact('groups', 'bulan', 'year', 'month'));
    }

    public function produksiPerKandang(Request $request)
    {
        $kandangId = $request->kandang_id;
        $kandangs = Kandang::orderBy('kode')->get();
        $productions = collect();
        if ($kandangId) {
            $productions = Production::with(['kandang', 'details.eggSize'])->where('kandang_id', $kandangId)->orderBy('tanggal', 'desc')->get();
        }
        return view('admin.laporan.produksi-per-kandang', compact('productions', 'kandangs', 'kandangId'));
    }

    public function hargaHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $prices = DailyPrice::with('eggSize')->where('tanggal', $tanggal)->orderBy('egg_size_id')->get();
        return view('admin.laporan.harga-harian', compact('prices', 'tanggal'));
    }

    public function stockGudang()
    {
        $stocks = Stock::with('eggSize')->get();
        return view('admin.laporan.stock-gudang', compact('stocks'));
    }

    public function keuanganHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $details = ProductionDetail::with(['production.kandang', 'eggSize'])
            ->whereHas('production', fn($q) => $q->where('tanggal', $tanggal))
            ->orderBy('production_id')->get();
        $groups = $details->groupBy(fn($d) => $d->production->production->kandang->nama ?? $d->production->kandang->kode ?? 'Lainnya');
        $total = $details->sum('subtotal');
        return view('admin.laporan.keuangan-harian', compact('groups', 'tanggal', 'total'));
    }

    public function keuanganBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        [$year, $month] = explode('-', $bulan);
        $details = ProductionDetail::with(['production.kandang', 'eggSize'])
            ->whereHas('production', fn($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month))
            ->orderBy('production_id')->get()
            ->groupBy(fn($d) => $d->production->tanggal->format('Y-m-d'));
        $total = $details->flatten()->sum('subtotal');
        return view('admin.laporan.keuangan-bulanan', compact('details', 'bulan', 'year', 'month', 'total'));
    }
}
