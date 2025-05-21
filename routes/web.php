<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StokManualController;
use App\Http\Controllers\UnitMasukController;
use App\Http\Controllers\UnitProblemController;
use App\Http\Controllers\MobilImportController;
use App\Http\Controllers\AddUserController;

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/stokmanual', [StokManualController::class, 'index'])->name('stokmanual'); // Menampilkan daftar stok manual
    Route::get('/stokmanual/{id}/edit', [StokManualController::class, 'edit'])->name('stokmanual.edit'); // Form edit stok manual
    Route::put('/stokmanual/{id}', [StokManualController::class, 'update'])->name('stokmanual.update'); // Update stok manual
    Route::delete('/stokmanual/{id}', [StokManualController::class, 'destroy'])->name('stokmanual.destroy'); // Hapus stok manual
    Route::get('/unitmasuk', [UnitMasukController::class, 'index'])->name('unitmasuk');
    Route::post('/unitmasuk', [UnitMasukController::class, 'store'])->name('unitmasuk.store');
    Route::put('/unitmasuk/{id}', [UnitMasukController::class, 'update'])->name('unitmasuk.update');
    Route::delete('/unitmasuk/{id}', [UnitMasukController::class, 'destroy'])->name('unitmasuk.destroy');
    Route::get('/unitproblem', [UnitProblemController::class, 'index'])->name('unitproblem');
    Route::post('/unitproblem', [UnitProblemController::class, 'store'])->name('unitproblem.store');
    Route::put('/unitproblem/{id}', [UnitProblemController::class, 'update'])->name('unitproblem.update');
    Route::delete('/unitproblem/{id}', [UnitProblemController::class, 'destroy'])->name('unitproblem.destroy');
    Route::get('/adduser', [AddUserController::class, 'index'])->name('adduser');
    Route::post('/adduser', [AddUserController::class, 'store'])->name('adduser.store');
    Route::delete('/adduser/{id}', [AddUserController::class, 'destroy'])->name('adduser.destroy');
    Route::post('/unitmasuk/{id}/status', [UnitMasukController::class, 'updateStatus'])->name('unitmasuk.status');
    Route::get('/import-mobil', [MobilImportController::class, 'showForm'])->name('mobil.form');
    Route::post('/import-mobil/masuk', [MobilImportController::class, 'importUnitMasuk'])->name('mobil.import');
    Route::post('/import-mobil/keluar', [MobilImportController::class, 'importUnitKeluar'])->name('mobil.importUnitKeluar');

});