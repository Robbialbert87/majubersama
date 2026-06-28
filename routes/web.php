<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BarnController;
use App\Http\Controllers\EggCategoryController;
use App\Http\Controllers\DailyPriceController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\LaporanController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::get('/barns', [BarnController::class, 'index'])->name('barns.index');
    Route::post('/barns', [BarnController::class, 'store'])->name('barns.store');
    Route::put('/barns/{barn}', [BarnController::class, 'update'])->name('barns.update');
    Route::delete('/barns/{barn}', [BarnController::class, 'destroy'])->name('barns.destroy');

    Route::get('/egg-categories', [EggCategoryController::class, 'index'])->name('egg-categories.index');
    Route::post('/egg-categories', [EggCategoryController::class, 'store'])->name('egg-categories.store');
    Route::put('/egg-categories/{eggCategory}', [EggCategoryController::class, 'update'])->name('egg-categories.update');
    Route::delete('/egg-categories/{eggCategory}', [EggCategoryController::class, 'destroy'])->name('egg-categories.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Transaksi
    Route::get('/daily-prices', [DailyPriceController::class, 'index'])->name('daily-prices.index');
    Route::post('/daily-prices', [DailyPriceController::class, 'store'])->name('daily-prices.store');
    Route::put('/daily-prices/{dailyPrice}', [DailyPriceController::class, 'update'])->name('daily-prices.update');
    Route::delete('/daily-prices/{dailyPrice}', [DailyPriceController::class, 'destroy'])->name('daily-prices.destroy');

    Route::get('/productions', [ProductionController::class, 'index'])->name('productions.index');
    Route::get('/productions/create', [ProductionController::class, 'create'])->name('productions.create');
    Route::post('/productions', [ProductionController::class, 'store'])->name('productions.store');
    Route::delete('/productions/{production}', [ProductionController::class, 'destroy'])->name('productions.destroy');

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

    // Gudang
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    // Laporan
    Route::get('/laporan/produksi-harian', [LaporanController::class, 'produksiHarian'])->name('laporan.produksi-harian');
    Route::get('/laporan/produksi-mingguan', [LaporanController::class, 'produksiMingguan'])->name('laporan.produksi-mingguan');
    Route::get('/laporan/produksi-bulanan', [LaporanController::class, 'produksiBulanan'])->name('laporan.produksi-bulanan');
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/stock-gudang', [LaporanController::class, 'stockGudang'])->name('laporan.stock-gudang');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
