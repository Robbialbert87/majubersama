<?php

namespace App\Http\Controllers;

use App\Models\EggSize;
use App\Models\Stock;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    const PAPAN_BUTIR = 30;
    const IKAT_PAPAN = 5;

    public function index(Request $request)
    {
        $tanggal = $request->tanggal;
        $sizes = EggSize::orderBy('urutan')->get();

        if ($tanggal) {
            $mutations = StockMutation::where('tanggal', '<=', $tanggal)
                ->select('egg_size_id',
                    DB::raw('SUM(CASE WHEN jenis = "in" THEN jumlah_butir ELSE -jumlah_butir END) as stok_butir'),
                    DB::raw('SUM(CASE WHEN jenis = "in" THEN jumlah_papan ELSE -jumlah_papan END) as stok_papan'),
                    DB::raw('SUM(CASE WHEN jenis = "in" THEN jumlah_ikat ELSE -jumlah_ikat END) as stok_ikat'))
                ->groupBy('egg_size_id')
                ->get()
                ->keyBy('egg_size_id');

            $stocks = $sizes->map(function ($s) use ($mutations) {
                $m = $mutations->get($s->id);
                $butir = $m ? max(0, (int)$m->stok_butir) : 0;
                $papan = $m ? max(0, (int)$m->stok_papan) : 0;
                $ikat = $m ? max(0, (int)$m->stok_ikat) : 0;
                return (object)[
                    'eggSize' => $s,
                    'egg_size_id' => $s->id,
                    'jumlah_butir' => $butir,
                    'jumlah_papan' => $papan,
                    'jumlah_ikat' => $ikat,
                    'updated_at' => null,
                ];
            });
        } else {
            $stocks = Stock::with('eggSize')->get();
        }

        $currentStocks = Stock::with('eggSize')->get()->keyBy('egg_size_id');

        return view('admin.stock.index', compact('stocks', 'sizes', 'tanggal', 'currentStocks'));
    }
}
