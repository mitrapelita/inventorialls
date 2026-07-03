<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/karyawan', [PageController::class, 'karyawan'])->name('karyawan');
Route::get('/master', [PageController::class, 'master'])->name('master');
Route::get('/validasi', [PageController::class, 'validasi'])->name('validasi');
Route::get('/transaksi', [PageController::class, 'transaksi'])->name('transaksi');
Route::get('/mutasi', [PageController::class, 'mutasi'])->name('mutasi');
Route::get('/laporan', [PageController::class, 'laporan'])->name('laporan');
Route::get('/log', [PageController::class, 'log'])->name('log');
