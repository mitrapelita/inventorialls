<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicValidationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserTicketController;
use App\Http\Controllers\ValidasiController;
use App\Models\ActivityLog;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── Redirect Root ke User Portal ──────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('user.dashboard');
});

// ─── Autentikasi ────────────────────────────────────────────────────────────
Route::get('/otoritasitinventory', [AuthController::class, 'showLogin'])->name('login');
Route::post('/otoritasitinventory', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Publik Validasi Fisik
Route::get('/toolsvalidasipublic', [PublicValidationController::class, 'index'])->name('toolsvalidasipublic');
Route::post('/toolsvalidasipublic/store', [PublicValidationController::class, 'store'])->name('public.validasi.store');

// Pencarian Data (AJAX) — Diakses frontend tanpa auth karena form tiket publik juga pakai ini
Route::get('/workspaceinventory/api/search/karyawan', [SearchController::class, 'searchKaryawan'])->name('api.search.karyawan');
Route::get('/workspaceinventory/api/search/inventory', [SearchController::class, 'searchInventory'])->name('api.search.inventory');
Route::get('/workspaceinventory/api/mutasi/rusak', [MutasiController::class, 'apiInventoryRusak'])->name('mutasi.api-rusak');

// ─── Admin (Dilindungi Middleware Auth) ─────────────────────────────────────
Route::prefix('workspaceinventory')->middleware('auth')->group(function () {

    // Dashboard & Halaman Statis
    Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/karyawan', [PageController::class, 'karyawan'])->name('karyawan');
    Route::get('/master', [PageController::class, 'master'])->name('master');
    Route::get('/validasi', [PageController::class, 'validasi'])->name('validasi');
    Route::get('/transaksi', [PageController::class, 'transaksi'])->name('transaksi');
    Route::get('/mutasi', [PageController::class, 'mutasi'])->name('mutasi');
    Route::get('/laporan', [PageController::class, 'laporan'])->name('laporan');
    Route::get('/log', [PageController::class, 'log'])->name('log');
    Route::get('/histori', [PageController::class, 'historiBarang'])->name('histori');

    // Pengaturan Admin
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan', [SettingController::class, 'store'])->name('pengaturan.store');
    Route::put('/pengaturan/{user}', [SettingController::class, 'update'])->name('pengaturan.update');
    Route::delete('/pengaturan/{user}', [SettingController::class, 'destroy'])->name('pengaturan.destroy');

    Route::post('/pengaturan/approver', [SettingController::class, 'storeApprover'])->name('pengaturan.approver.store');
    Route::put('/pengaturan/approver/{approver}', [SettingController::class, 'updateApprover'])->name('pengaturan.approver.update');
    Route::delete('/pengaturan/approver/{approver}', [SettingController::class, 'destroyApprover'])->name('pengaturan.approver.destroy');

    Route::post('/pengaturan/update-pin', [SettingController::class, 'updatePin'])->name('pengaturan.updatePin');

    // Manajemen Tiket
    Route::get('/tiket', function () {
        return view('admin.tiket');
    })->name('admin-tiket');
    Route::post('/tiket', [TicketController::class, 'store'])->name('ticket.store');
    Route::delete('/tiket/{ticket}', [TicketController::class, 'destroy'])->name('ticket.destroy');

    // Validasi Transaksi
    Route::post('/validasi/{transaction}/approve', [ValidasiController::class, 'approve'])->name('validasi.approve');
    Route::post('/validasi/{transaction}/reject', [ValidasiController::class, 'reject'])->name('validasi.reject');

    // CRUD Inventaris (Master)
    Route::post('/master', [InventoryController::class, 'store'])->name('inventory.store');
    Route::post('/master/import', [InventoryController::class, 'import'])->name('inventory.import');
    Route::put('/master/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/master/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    // CRUD Karyawan
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/karyawan/{user}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{user}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    Route::post('/karyawan/{id}/restore', [KaryawanController::class, 'restore'])->name('karyawan.restore');

    // Transaksi dari Dashboard Admin
    Route::post('/transaksi/serah-terima', [TransaksiController::class, 'storeSerahTerima'])->name('transaksi.serah-terima');
    Route::post('/transaksi/peminjaman', [TransaksiController::class, 'storePeminjaman'])->name('transaksi.peminjaman');
    Route::post('/transaksi/penukaran', [TransaksiController::class, 'storePenukaran'])->name('transaksi.penukaran');
    Route::post('/transaksi/pengembalian', [TransaksiController::class, 'storePengembalian'])->name('transaksi.pengembalian');
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
    Route::post('/master/bulk-delete', [InventoryController::class, 'bulkDestroy'])->name('inventory.bulk-destroy');
    Route::post('/karyawan/bulk-delete', [KaryawanController::class, 'bulkDestroy'])->name('karyawan.bulk-destroy');
    Route::post('/transaksi/bulk-delete', [TransaksiController::class, 'bulkDestroy'])->name('transaksi.bulk-destroy');
    Route::post('/tiket/bulk-delete', [TicketController::class, 'bulkDestroy'])->name('ticket.bulk-destroy');
    Route::post('/pengaturan/bulk-delete', [SettingController::class, 'bulkDestroy'])->name('pengaturan.bulk-destroy');
    Route::post('/pengaturan/approver/bulk-delete', [SettingController::class, 'bulkDestroyApprover'])->name('pengaturan.approver.bulk-destroy');
    Route::post('/mutasi/masuk/bulk-delete', [MutasiController::class, 'bulkDestroyMasuk'])->name('mutasi.bulk-destroy-masuk');
    Route::post('/mutasi/bulk-delete', [MutasiController::class, 'bulkDestroyKeluar'])->name('mutasi.bulk-destroy-keluar');
    Route::post('/log/bulk-delete', function (Request $request) {
        if ($request->pin !== '447747') {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }
        ActivityLog::whereIn('id', $request->ids ?? [])->delete();

        return response()->json(['success' => true]);
    })->name('log.bulk-destroy');
    Route::post('/histori/bulk-delete', function (Request $request) {
        if ($request->pin !== '447747') {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }
        TransactionItem::whereIn('id', $request->ids ?? [])->delete();

        return response()->json(['success' => true]);
    })->name('histori.bulk-destroy');
});

// ─── Form Tiket Karyawan (Publik, tanpa auth) ───────────────────────────────
Route::get('/tiket/{type}', [PageController::class, 'tiket'])->name('tiket');
Route::post('/tiket/{type}', [UserTicketController::class, 'submit'])->name('tiket.submit');

// ─── User Portal (Publik) ───────────────────────────────────────────────────
Route::prefix('user')->group(function () {
    Route::get('/', [PageController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/serah-terima', [PageController::class, 'userSerahTerima'])->name('user.serah-terima');
    Route::get('/peminjaman', [PageController::class, 'userPeminjaman'])->name('user.peminjaman');
    Route::get('/penukaran', [PageController::class, 'userPenukaran'])->name('user.penukaran');
    Route::get('/cek-aset', [PublicValidationController::class, 'indexUser'])->name('user.cek-aset');
});
