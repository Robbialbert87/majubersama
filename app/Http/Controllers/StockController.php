<?php

namespace App\Http\Controllers;

use App\Models\EggCategory;
use App\Models\Stock;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $categories = EggCategory::orderBy('urutan')->get();
        $stocks = Stock::with('eggCategory')->get()->keyBy('egg_category_id');
        $settings = SystemSetting::first();
        $butirPerPapan = $settings->butir_per_papan ?? 30;
        $papanPerIkat = $settings->papan_per_ikat ?? 5;

        return view('admin.stock.index', compact('categories', 'stocks', 'butirPerPapan', 'papanPerIkat'));
    }
}
