<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IntegrasiController;
use App\Http\Controllers\KampungController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PendudukController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kampung', KampungController::class);

    Route::get('penduduk', [PendudukController::class, 'index'])->name('penduduk.index');
    Route::post('penduduk', [PendudukController::class, 'store'])->name('penduduk.store');
    Route::put('penduduk/{penduduk}', [PendudukController::class, 'update'])->name('penduduk.update');
    Route::delete('penduduk/{penduduk}', [PendudukController::class, 'destroy'])->name('penduduk.destroy');

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/tambah', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('laporan', [LaporanController::class, 'store'])->name('laporan.store');

    Route::get('integrasi/rekap-kecamatan', [IntegrasiController::class, 'rekapKecamatan'])->name('integrasi.rekap');

    // Khusus admin kecamatan
    Route::middleware('role:admin_kecamatan')->group(function () {
        Route::put('laporan/{laporan}/verifikasi', [LaporanController::class, 'verifikasi'])->name('laporan.verifikasi');
        Route::post('integrasi/sinkronkan', [IntegrasiController::class, 'sinkronkanKeKecamatan'])->name('integrasi.sinkron');
    });
});
