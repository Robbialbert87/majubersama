<?php

namespace App\Http\Controllers;

use App\Models\DailyPrice;
use App\Models\EggSize;
use Illuminate\Http\Request;

class DailyPriceController extends Controller
{
    public function index()
    {
        $prices = DailyPrice::with(['eggSize', 'creator'])->orderBy('tanggal', 'desc')->orderBy('egg_size_id')->get();
        $sizes = EggSize::where('status', 'Active')->orderBy('urutan')->get();
        return view('admin.daily-prices.index', compact('prices', 'sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'egg_size_id' => 'required|exists:egg_sizes,id',
            'harga_per_butir' => 'required|integer|min:0',
        ]);

        if (DailyPrice::where('tanggal', $validated['tanggal'])->where('egg_size_id', $validated['egg_size_id'])->exists()) {
            return redirect()->route('daily-prices.index')->with('error', 'Harga untuk ukuran ini pada tanggal ' . $validated['tanggal'] . ' sudah ada.');
        }

        $validated['created_by'] = auth()->id();
        DailyPrice::create($validated);
        return redirect()->route('daily-prices.index')->with('success', 'Harga harian berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $price = DailyPrice::findOrFail($id);
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'egg_size_id' => 'required|exists:egg_sizes,id',
            'harga_per_butir' => 'required|integer|min:0',
        ]);

        if (DailyPrice::where('tanggal', $validated['tanggal'])->where('egg_size_id', $validated['egg_size_id'])->where('id', '!=', $id)->exists()) {
            return redirect()->route('daily-prices.index')->with('error', 'Harga untuk ukuran ini pada tanggal ' . $validated['tanggal'] . ' sudah ada.');
        }

        $price->update($validated);
        return redirect()->route('daily-prices.index')->with('success', 'Harga harian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DailyPrice::findOrFail($id)->delete();
        return redirect()->route('daily-prices.index')->with('success', 'Harga harian berhasil dihapus.');
    }
}
