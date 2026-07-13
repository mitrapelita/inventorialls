<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserTicketController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MutasiController;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

// ─── Redirect Root ke User Portal ──────────────────────────────────────────────
Route::get('/', function () { return redirect()->route('user.dashboard'); });

// ─── Autentikasi ────────────────────────────────────────────────────────────
Route::get('/otoritasitinventory', [AuthController::class, 'showLogin'])->name('login');
Route::post('/otoritasitinventory', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pencarian Data (AJAX) — Diakses frontend tanpa auth karena form tiket publik juga pakai ini
Route::get('/workspaceinventory/api/search/karyawan',  [SearchController::class, 'searchKaryawan'])->name('api.search.karyawan');
Route::get('/workspaceinventory/api/search/inventory', [SearchController::class, 'searchInventory'])->name('api.search.inventory');
Route::get('/workspaceinventory/api/mutasi/rusak', [MutasiController::class, 'apiInventoryRusak'])->name('mutasi.api-rusak');

// ─── Admin (Dilindungi Middleware Auth) ─────────────────────────────────────
Route::prefix('workspaceinventory')->middleware('auth')->group(function () {

    // Dashboard & Halaman Statis
    Route::get('/',          [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/karyawan',  [PageController::class, 'karyawan'])->name('karyawan');
    Route::get('/master',    [PageController::class, 'master'])->name('master');
    Route::get('/validasi',  [PageController::class, 'validasi'])->name('validasi');
    Route::get('/transaksi', [PageController::class, 'transaksi'])->name('transaksi');
    Route::get('/mutasi',    [PageController::class, 'mutasi'])->name('mutasi');
    Route::get('/laporan',   [PageController::class, 'laporan'])->name('laporan');
    Route::get('/log',       [PageController::class, 'log'])->name('log');
    Route::get('/histori',   [PageController::class, 'historiBarang'])->name('histori');

    // Pengaturan Admin
    Route::get('/pengaturan', [App\Http\Controllers\SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan', [App\Http\Controllers\SettingController::class, 'store'])->name('pengaturan.store');
    Route::put('/pengaturan/{user}', [App\Http\Controllers\SettingController::class, 'update'])->name('pengaturan.update');
    Route::delete('/pengaturan/{user}', [App\Http\Controllers\SettingController::class, 'destroy'])->name('pengaturan.destroy');

    Route::post('/pengaturan/approver', [App\Http\Controllers\SettingController::class, 'storeApprover'])->name('pengaturan.approver.store');
    Route::put('/pengaturan/approver/{approver}', [App\Http\Controllers\SettingController::class, 'updateApprover'])->name('pengaturan.approver.update');
    Route::delete('/pengaturan/approver/{approver}', [App\Http\Controllers\SettingController::class, 'destroyApprover'])->name('pengaturan.approver.destroy');

    // Manajemen Tiket
    Route::get('/tiket',             function () { return view('admin.tiket'); })->name('admin-tiket');
    Route::post('/tiket',            [TicketController::class, 'store'])->name('ticket.store');
    Route::delete('/tiket/{ticket}', [TicketController::class, 'destroy'])->name('ticket.destroy');

    // Validasi Transaksi
    Route::post('/validasi/{transaction}/approve', [ValidasiController::class, 'approve'])->name('validasi.approve');
    Route::post('/validasi/{transaction}/reject',  [ValidasiController::class, 'reject'])->name('validasi.reject');

    // CRUD Inventaris (Master)
    Route::post('/master',               [InventoryController::class, 'store'])->name('inventory.store');
    Route::put('/master/{inventory}',    [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/master/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    // CRUD Karyawan
    Route::post('/karyawan',             [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/karyawan/{user}',       [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{user}',    [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    Route::post('/karyawan/{id}/restore',[KaryawanController::class, 'restore'])->name('karyawan.restore');

    // Transaksi dari Dashboard Admin
    Route::post('/transaksi/serah-terima',  [TransaksiController::class, 'storeSerahTerima'])->name('transaksi.serah-terima');
    Route::post('/transaksi/peminjaman',    [TransaksiController::class, 'storePeminjaman'])->name('transaksi.peminjaman');
    Route::post('/transaksi/penukaran',     [TransaksiController::class, 'storePenukaran'])->name('transaksi.penukaran');
    Route::post('/transaksi/pengembalian',  [TransaksiController::class, 'storePengembalian'])->name('transaksi.pengembalian');
    Route::get('/transaksi/{transaction}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
    Route::get('/transaksi/mptb/{user}/print', [TransaksiController::class, 'printMptb'])->name('transaksi.print-mptb');
    
    // Mutasi Barang
    Route::post('/mutasi/masuk', [MutasiController::class, 'storeMasuk'])->name('mutasi.masuk');
    Route::post('/mutasi/keluar', [MutasiController::class, 'storeKeluar'])->name('mutasi.keluar');
    Route::post('/mutasi/{transaction}/terima', [MutasiController::class, 'terimaKembali'])->name('mutasi.terima');
    Route::delete('/mutasi/masuk/{id}', [MutasiController::class, 'destroyMasuk'])->name('mutasi.destroy-masuk');
    Route::delete('/mutasi/{transaction}', [MutasiController::class, 'destroyKeluar'])->name('mutasi.destroy');
    Route::get('/mutasi/{transaction}/print', [MutasiController::class, 'printKeluar'])->name('mutasi.print');
    
    // ─── Bulk Delete (PIN Protected) ─────────────────────────────────────────
    Route::post('/master/bulk-delete',    [InventoryController::class, 'bulkDestroy'])->name('inventory.bulk-destroy');
    Route::post('/karyawan/bulk-delete',  [KaryawanController::class, 'bulkDestroy'])->name('karyawan.bulk-destroy');
    Route::post('/transaksi/bulk-delete', [TransaksiController::class, 'bulkDestroy'])->name('transaksi.bulk-destroy');
    Route::post('/tiket/bulk-delete',     [TicketController::class, 'bulkDestroy'])->name('ticket.bulk-destroy');
    Route::post('/pengaturan/bulk-delete',[App\Http\Controllers\SettingController::class, 'bulkDestroy'])->name('pengaturan.bulk-destroy');
    Route::post('/pengaturan/approver/bulk-delete', [App\Http\Controllers\SettingController::class, 'bulkDestroyApprover'])->name('pengaturan.approver.bulk-destroy');
    Route::post('/mutasi/masuk/bulk-delete', [MutasiController::class, 'bulkDestroyMasuk'])->name('mutasi.bulk-destroy-masuk');
    Route::post('/mutasi/bulk-delete',    [MutasiController::class, 'bulkDestroyKeluar'])->name('mutasi.bulk-destroy-keluar');
    Route::post('/log/bulk-delete', function(Request $request) {
        if ($request->pin !== '447747') return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        ActivityLog::whereIn('id', $request->ids ?? [])->delete();
        return response()->json(['success' => true]);
    })->name('log.bulk-destroy');
    Route::post('/histori/bulk-delete', function(Request $request) {
        if ($request->pin !== '447747') return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        \App\Models\TransactionItem::whereIn('id', $request->ids ?? [])->delete();
        return response()->json(['success' => true]);
    })->name('histori.bulk-destroy');
});

// ─── Form Tiket Karyawan (Publik, tanpa auth) ───────────────────────────────
Route::get('/tiket/{type}',  [PageController::class, 'tiket'])->name('tiket');
Route::post('/tiket/{type}', [UserTicketController::class, 'submit'])->name('tiket.submit');

// ─── User Portal (Publik) ───────────────────────────────────────────────────
Route::prefix('user')->group(function () {
    Route::get('/',             [PageController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/serah-terima', [PageController::class, 'userSerahTerima'])->name('user.serah-terima');
    Route::get('/peminjaman',   [PageController::class, 'userPeminjaman'])->name('user.peminjaman');
    Route::get('/penukaran',    [PageController::class, 'userPenukaran'])->name('user.penukaran');
});
