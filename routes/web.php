<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\RuanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::resource('barangs', BarangController::class);
Route::resource('ruangans', RuanganController::class);
Route::resource('kategori-barangs', KategoriBarangController::class)
    ->parameters(['kategori-barangs' => 'kategoriBarang']);
Route::resource('laporan-kerusakan', LaporanKerusakanController::class)
    ->parameters(['laporan-kerusakan' => 'laporanKerusakan']);
