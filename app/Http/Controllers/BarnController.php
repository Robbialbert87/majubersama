<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use Illuminate\Http\Request;

class BarnController extends Controller
{
    public function index()
    {
        $barns = Barn::orderBy('kode')->get();
        return view('admin.barns.index', compact('barns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:barns',
            'nama' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:200',
            'kapasitas_ayam' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        Barn::create($validated);
        return redirect()->route('barns.index')->with('success', 'Kandang "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $barn = Barn::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:barns,kode,' . $id,
            'nama' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:200',
            'kapasitas_ayam' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        $barn->update($validated);
        return redirect()->route('barns.index')->with('success', 'Kandang "' . $validated['nama'] . '" berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barn = Barn::findOrFail($id);
        if ($barn->productions()->exists()) {
            return redirect()->route('barns.index')->with('error', 'Kandang "' . $barn->nama . '" tidak dapat dihapus karena masih memiliki data produksi.');
        }
        $barn->delete();
        return redirect()->route('barns.index')->with('success', 'Kandang "' . $barn->nama . '" berhasil dihapus.');
    }
}
