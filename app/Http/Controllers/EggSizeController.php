<?php

namespace App\Http\Controllers;

use App\Models\EggSize;
use Illuminate\Http\Request;

class EggSizeController extends Controller
{
    public function index()
    {
        $sizes = EggSize::orderBy('urutan')->get();
        return view('admin.egg-sizes.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:egg_sizes',
            'nama' => 'required|string|max:100',
            'urutan' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        EggSize::create($validated);
        return redirect()->route('egg-sizes.index')->with('success', 'Ukuran "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $size = EggSize::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:egg_sizes,kode,' . $id,
            'nama' => 'required|string|max:100',
            'urutan' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        $size->update($validated);
        return redirect()->route('egg-sizes.index')->with('success', 'Ukuran "' . $validated['nama'] . '" berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $size = EggSize::findOrFail($id);
        if ($size->dailyPrices()->exists() || $size->productionDetails()->exists()) {
            return redirect()->route('egg-sizes.index')->with('error', 'Ukuran "' . $size->nama . '" tidak dapat dihapus karena masih memiliki data.');
        }
        $size->delete();
        return redirect()->route('egg-sizes.index')->with('success', 'Ukuran "' . $size->nama . '" berhasil dihapus.');
    }
}
