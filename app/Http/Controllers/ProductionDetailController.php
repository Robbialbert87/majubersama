<?php

namespace App\Http\Controllers;

use App\Models\DailyPrice;
use App\Models\EggSize;
use App\Models\Stock;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionDetailController extends Controller
{
    const PAPAN_BUTIR = 30;
    const IKAT_PAPAN = 5;

    public function index()
    {
        $productions = Production::with(['kandang', 'details.eggSize'])->whereHas('details')->orderBy('tanggal', 'desc')->get();
        return view('admin.production-details.index', compact('productions'));
    }

    public function create(Request $request)
    {
        $kandangs = \App\Models\Kandang::where('status', 'Active')->orderBy('kode')->get();
        $sizes = EggSize::where('status', 'Active')->orderBy('urutan')->get();

        $selectedTanggal = $request->tanggal ?? now()->format('Y-m-d');
        $selectedKandang = $request->kandang_id ?? null;

        $hargaMap = [];
        foreach ($sizes as $s) {
            $hp = DailyPrice::where('egg_size_id', $s->id)
                ->whereDate('tanggal', '<=', $selectedTanggal)
                ->orderBy('tanggal', 'desc')
                ->value('harga_per_butir');
            $hargaMap[$s->id] = $hp ?? 0;
        }

        $existingDetails = [];
        if ($selectedKandang) {
            $prod = Production::whereDate('tanggal', $selectedTanggal)->where('kandang_id', $selectedKandang)->first();
            if ($prod) {
                $existingDetails = ProductionDetail::where('production_id', $prod->id)->get()->keyBy('egg_size_id');
            }
        }

        return view('admin.production-details.create', compact('kandangs', 'sizes', 'selectedTanggal', 'selectedKandang', 'hargaMap', 'existingDetails'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'                    => 'required|date',
            'kandang_id'                 => 'required|exists:kandang,id',
            'details'                    => 'required|array|min:1',
            'details.*.egg_size_id'      => 'required|exists:egg_sizes,id',
            'details.*.jumlah_ikat'      => 'nullable|integer|min:0',
            'details.*.jumlah_papan'     => 'nullable|integer|min:0',
            'details.*.jumlah_butir'     => 'nullable|integer|min:0',
            'details.*.sisa_butir'       => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $production = Production::firstOrCreate(
                [
                    'tanggal' => $validated['tanggal'],
                    'kandang_id' => $validated['kandang_id']
                ],
                [
                    'ayam_besar' => 0,
                    'ayam_kecil' => 0,
                    'total_produksi' => 0,
                    'telur_putih' => 0,
                    'telur_pecah' => 0,
                    'catatan' => 'Auto-generated dari form sortir',
                    'created_by' => auth()->id() ?? 1
                ]
            );

            foreach ($validated['details'] as $d) {
                $inIkat = (int)($d['jumlah_ikat'] ?? 0);
                $inPapan = (int)($d['jumlah_papan'] ?? 0);
                $inButir = (int)($d['jumlah_butir'] ?? 0);
                $inSisa = (int)($d['sisa_butir'] ?? 0);
                
                // Total butir: hitung berdasarkan jumlah butir yang diisikan
                $butir = $inButir;

                // Cek apakah sudah ada sortir untuk ukuran ini di produksi ini
                $existing = ProductionDetail::where('production_id', $production->id)
                    ->where('egg_size_id', $d['egg_size_id'])
                    ->first();

                if ($butir <= 0) {
                    if ($existing) {
                        // Jika diubah jadi 0, kembalikan stok lama dan hapus detail
                        $stock = Stock::where('egg_size_id', $d['egg_size_id'])->first();
                        if ($stock) {
                            $stock->update([
                                'jumlah_butir' => max(0, $stock->jumlah_butir - $existing->jumlah_butir),
                                'updated_at'   => now(),
                            ]);
                        }
                        
                        StockMutation::create([
                            'tanggal'      => $production->tanggal,
                            'egg_size_id'  => $d['egg_size_id'],
                            'jenis'        => 'out',
                            'jumlah_butir' => $existing->jumlah_butir,
                            'jumlah_papan' => $existing->jumlah_papan,
                            'jumlah_ikat'  => $existing->jumlah_ikat,
                            'keterangan'   => 'Hapus sortir (ubah ke 0) #' . $production->id . ' - ' . ($production->kandang->kode ?? ''),
                        ]);

                        $existing->delete();
                    }
                    continue;
                }

                // Hitung ulang standar papan & ikat untuk database (menormalkan nilai)
                $papan = intdiv($butir, self::PAPAN_BUTIR);
                $ikat  = intdiv($papan, self::IKAT_PAPAN);

                // Harga berdasarkan tanggal produksi (yang paling dekat sebelum atau sama)
                $tglStr = \Carbon\Carbon::parse($production->tanggal)->format('Y-m-d');
                $harga = DailyPrice::where('egg_size_id', $d['egg_size_id'])
                    ->whereDate('tanggal', '<=', $tglStr)
                    ->orderBy('tanggal', 'desc')
                    ->value('harga_per_butir') ?? 0;

                $subtotal = $butir * $harga;

                if ($existing) {
                    // Kembalikan stok lama dulu sebelum update
                    $stock = Stock::where('egg_size_id', $d['egg_size_id'])->first();
                    if ($stock) {
                        $stock->update([
                            'jumlah_butir' => max(0, $stock->jumlah_butir - $existing->jumlah_butir),
                            'updated_at'   => now(),
                        ]);
                    }
                    $existing->update([
                        'jumlah_butir'   => $butir,
                        'jumlah_papan'   => $papan,
                        'jumlah_ikat'    => $ikat,
                        'harga_per_butir' => $harga,
                        'subtotal'       => $subtotal,
                        'sisa_butir'     => $inSisa,
                    ]);
                } else {
                    ProductionDetail::create([
                        'production_id'  => $production->id,
                        'egg_size_id'    => $d['egg_size_id'],
                        'jumlah_butir'   => $butir,
                        'jumlah_papan'   => $papan,
                        'jumlah_ikat'    => $ikat,
                        'harga_per_butir' => $harga,
                        'subtotal'       => $subtotal,
                        'sisa_butir'     => $inSisa,
                    ]);
                }

                // Update stok
                Stock::updateOrCreate(
                    ['egg_size_id' => $d['egg_size_id']],
                    ['jumlah_butir' => \DB::raw("jumlah_butir + {$butir}"), 'updated_at' => now()]
                );

                StockMutation::create([
                    'tanggal'      => $production->tanggal,
                    'egg_size_id'  => $d['egg_size_id'],
                    'jenis'        => 'in',
                    'jumlah_butir' => $butir,
                    'jumlah_papan' => $papan,
                    'jumlah_ikat'  => $ikat,
                    'keterangan'   => 'Sortir produksi #' . $production->id . ' - ' . ($production->kandang->kode ?? ''),
                ]);
            }
        });

        return redirect()->route('production-details.index')->with('success', 'Hasil sortir berhasil disimpan dan stok diperbarui.');
    }

    public function destroy($id)
    {
        $detail = ProductionDetail::findOrFail($id);
        $productionId = $detail->production_id;

        DB::transaction(function () use ($detail) {
            $stock = Stock::where('egg_size_id', $detail->egg_size_id)->first();
            if ($stock) {
                $newButir = max(0, $stock->jumlah_butir - $detail->jumlah_butir);
                $stock->update(['jumlah_butir' => $newButir, 'updated_at' => now()]);

                StockMutation::create([
                    'tanggal' => $detail->production->tanggal ?? now(),
                    'egg_size_id' => $detail->egg_size_id,
                    'jenis' => 'out',
                    'jumlah_butir' => $detail->jumlah_butir,
                    'jumlah_papan' => $detail->jumlah_papan,
                    'jumlah_ikat' => $detail->jumlah_ikat,
                    'keterangan' => 'Hapus sortir',
                ]);
            }
            $detail->delete();
        });

        return redirect()->route('production-details.index')->with('success', 'Detail sortir berhasil dihapus.');
    }
}
