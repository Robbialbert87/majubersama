<?php

namespace App\Http\Controllers;

use App\Models\DailyPrice;
use App\Models\EggSize;
use App\Models\Stock;
use App\Models\Kandang;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    const PAPAN_BUTIR = 30;
    const IKAT_PAPAN = 5;

    public function index()
    {
        $productions = Production::with(['kandang', 'creator', 'details.eggSize'])->orderBy('tanggal', 'desc')->orderBy('kandang_id')->get();
        return view('admin.productions.index', compact('productions'));
    }



    public function destroy($id)
    {
        $production = Production::with('details')->findOrFail($id);

        DB::transaction(function () use ($production) {
            foreach ($production->details as $d) {
                $stock = Stock::where('egg_size_id', $d->egg_size_id)->first();
                if ($stock) {
                    $newButir = max(0, $stock->jumlah_butir - $d->jumlah_butir);
                    $stock->update(['jumlah_butir' => $newButir, 'updated_at' => now()]);
                }

                StockMutation::create([
                    'tanggal' => $production->tanggal,
                    'egg_size_id' => $d->egg_size_id,
                    'jenis' => 'out',
                    'jumlah_butir' => $d->jumlah_butir,
                    'jumlah_papan' => $d->jumlah_papan,
                    'jumlah_ikat' => $d->jumlah_ikat,
                    'keterangan' => 'Hapus produksi',
                ]);
            }
            $production->delete();
        });

        return redirect()->route('productions.index')->with('success', 'Produksi berhasil dihapus, stok disesuaikan.');
    }
}
