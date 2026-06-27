<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\EggSizeController;
use App\Http\Controllers\DailyPriceController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductionDetailController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\LaporanController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/kandang', [KandangController::class, 'index'])->name('kandang.index');
    Route::post('/kandang', [KandangController::class, 'store'])->name('kandang.store');
    Route::put('/kandang/{kandang}', [KandangController::class, 'update'])->name('kandang.update');
    Route::delete('/kandang/{kandang}', [KandangController::class, 'destroy'])->name('kandang.destroy');

    Route::get('/egg-sizes', [EggSizeController::class, 'index'])->name('egg-sizes.index');
    Route::post('/egg-sizes', [EggSizeController::class, 'store'])->name('egg-sizes.store');
    Route::put('/egg-sizes/{eggSize}', [EggSizeController::class, 'update'])->name('egg-sizes.update');
    Route::delete('/egg-sizes/{eggSize}', [EggSizeController::class, 'destroy'])->name('egg-sizes.destroy');

    Route::get('/daily-prices', [DailyPriceController::class, 'index'])->name('daily-prices.index');
    Route::post('/daily-prices', [DailyPriceController::class, 'store'])->name('daily-prices.store');
    Route::put('/daily-prices/{dailyPrice}', [DailyPriceController::class, 'update'])->name('daily-prices.update');
    Route::delete('/daily-prices/{dailyPrice}', [DailyPriceController::class, 'destroy'])->name('daily-prices.destroy');

    Route::get('/productions', [ProductionController::class, 'index'])->name('productions.index');
    Route::delete('/productions/{production}', [ProductionController::class, 'destroy'])->name('productions.destroy');

    Route::get('/production-details', [ProductionDetailController::class, 'index'])->name('production-details.index');
    Route::get('/production-details/create', [ProductionDetailController::class, 'create'])->name('production-details.create');
    Route::post('/production-details', [ProductionDetailController::class, 'store'])->name('production-details.store');
    Route::delete('/production-details/{productionDetail}', [ProductionDetailController::class, 'destroy'])->name('production-details.destroy');

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    Route::get('/laporan/produksi-harian', [LaporanController::class, 'produksiHarian'])->name('laporan.produksi-harian');
    Route::get('/laporan/produksi-mingguan', [LaporanController::class, 'produksiMingguan'])->name('laporan.produksi-mingguan');
    Route::get('/laporan/produksi-bulanan', [LaporanController::class, 'produksiBulanan'])->name('laporan.produksi-bulanan');
    Route::get('/laporan/produksi-per-kandang', [LaporanController::class, 'produksiPerKandang'])->name('laporan.produksi-per-kandang');
    Route::get('/laporan/harga-harian', [LaporanController::class, 'hargaHarian'])->name('laporan.harga-harian');
    Route::get('/laporan/stock-gudang', [LaporanController::class, 'stockGudang'])->name('laporan.stock-gudang');
    Route::get('/laporan/keuangan-harian', [LaporanController::class, 'keuanganHarian'])->name('laporan.keuangan-harian');
    Route::get('/laporan/keuangan-bulanan', [LaporanController::class, 'keuanganBulanan'])->name('laporan.keuangan-bulanan');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
