<?php

namespace App\Http\Controllers;

use App\Models\DailyPrice;
use App\Models\EggCategory;
use Illuminate\Http\Request;

class DailyPriceController extends Controller
{
    public function index()
    {
        $prices = DailyPrice::with('creator')->orderBy('tanggal_berlaku', 'desc')->get();
        $categories = EggCategory::where('status', 'Active')->where('unit_penjualan', '!=', 'tidak')->orderBy('urutan')->get();
        return view('admin.daily-prices.index', compact('prices', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_berlaku' => 'required|date',
            'jumbo' => 'required|integer|min:0',
            'besar' => 'required|integer|min:0',
            'sedang' => 'required|integer|min:0',
            'kecil' => 'required|integer|min:0',
            'putih' => 'required|integer|min:0',
        ]);

        if (DailyPrice::where('tanggal_berlaku', $validated['tanggal_berlaku'])->exists()) {
            return redirect()->route('daily-prices.index')->with('error', 'Harga untuk tanggal ' . $validated['tanggal_berlaku'] . ' sudah ada. Silakan edit.');
        }

        $validated['created_by'] = auth()->id();
        DailyPrice::create($validated);
        return redirect()->route('daily-prices.index')->with('success', 'Harga per tanggal berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $price = DailyPrice::findOrFail($id);
        $validated = $request->validate([
            'tanggal_berlaku' => 'required|date',
            'jumbo' => 'required|integer|min:0',
            'besar' => 'required|integer|min:0',
            'sedang' => 'required|integer|min:0',
            'kecil' => 'required|integer|min:0',
            'putih' => 'required|integer|min:0',
        ]);

        if (DailyPrice::where('tanggal_berlaku', $validated['tanggal_berlaku'])->where('id', '!=', $id)->exists()) {
            return redirect()->route('daily-prices.index')->with('error', 'Harga untuk tanggal ' . $validated['tanggal_berlaku'] . ' sudah ada.');
        }

        $price->update($validated);
        return redirect()->route('daily-prices.index')->with('success', 'Harga per tanggal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DailyPrice::findOrFail($id)->delete();
        return redirect()->route('daily-prices.index')->with('success', 'Harga per tanggal berhasil dihapus.');
    }
}
