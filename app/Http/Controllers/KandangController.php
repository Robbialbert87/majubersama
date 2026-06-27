<?php

namespace App\Http\Controllers;

use App\Models\Kandang;
use Illuminate\Http\Request;

class KandangController extends Controller
{
    public function index()
    {
        $kandangs = Kandang::orderBy('kode')->get();
        return view('admin.kandang.index', compact('kandangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:kandang',
            'nama' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:200',
            'kapasitas_ayam' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        Kandang::create($validated);
        return redirect()->route('kandang.index')->with('success', 'Kandang "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kandang = Kandang::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:kandang,kode,' . $id,
            'nama' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:200',
            'kapasitas_ayam' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        $kandang->update($validated);
        return redirect()->route('kandang.index')->with('success', 'Kandang "' . $validated['nama'] . '" berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kandang = Kandang::findOrFail($id);
        if ($kandang->productions()->exists()) {
            return redirect()->route('kandang.index')->with('error', 'Kandang "' . $kandang->nama . '" tidak dapat dihapus karena masih memiliki data produksi.');
        }
        $kandang->delete();
        return redirect()->route('kandang.index')->with('success', 'Kandang "' . $kandang->nama . '" berhasil dihapus.');
    }
}
