<?php

namespace App\Http\Controllers;

use App\Models\EggCategory;
use Illuminate\Http\Request;

class EggCategoryController extends Controller
{
    public function index()
    {
        $categories = EggCategory::orderBy('urutan')->get();
        return view('admin.egg-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:egg_categories',
            'nama' => 'required|string|max:100',
            'unit_penjualan' => 'required|string|in:papan,ikat',
            'urutan' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        EggCategory::create($validated);
        return redirect()->route('egg-categories.index')->with('success', 'Kategori "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = EggCategory::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:egg_categories,kode,' . $id,
            'nama' => 'required|string|max:100',
            'unit_penjualan' => 'required|string|in:papan,ikat',
            'urutan' => 'required|integer|min:0',
            'status' => 'required|string|in:Active,Inactive',
        ]);
        $category->update($validated);
        return redirect()->route('egg-categories.index')->with('success', 'Kategori "' . $validated['nama'] . '" berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = EggCategory::findOrFail($id);
        if ($category->stocks()->exists() || $category->productionItems()->exists() || $category->saleDetails()->exists()) {
            return redirect()->route('egg-categories.index')->with('error', 'Kategori "' . $category->nama . '" tidak dapat dihapus karena masih memiliki data.');
        }
        $category->delete();
        return redirect()->route('egg-categories.index')->with('success', 'Kategori "' . $category->nama . '" berhasil dihapus.');
    }
}
