---
name: ventorialls_project_context
description: >
  Konteks lengkap proyek Ventorialls IT Inventory Management System.
  Trigger ketika membahas fitur karyawan, transaksi, inventaris, tiket,
  atau apapun terkait proyek Ventorialls.
---

# Ventorialls — IT Inventory Management System

## Tech Stack
- **Backend**: Laravel 12 (PHP 8.2), MariaDB
- **Frontend**: Alpine.js (via CDN), Blade templates, Vanilla CSS + Vite
- **Dev Server**: `php artisan serve` + `npm run dev` (jalan bersamaan)
- **URL lokal**: http://127.0.0.1:8000/workspaceinventory

## Struktur Utama
- `app/Http/Controllers/KaryawanController.php` — CRUD karyawan
- `app/Http/Controllers/TransaksiController.php` — serah terima, peminjaman, penukaran, pengembalian
- `app/Http/Controllers/ValidasiController.php` — approve/tolak tiket & update inventory
- `app/Http/Controllers/UserTicketController.php` — submit form tiket dari sisi karyawan
- `app/Http/Controllers/PageController.php` — halaman utama (dashboard, master, karyawan, dll)
- `app/Http/Controllers/InventoryController.php` — CRUD inventaris dari master data
- `app/Models/User.php` — model karyawan (role: 'karyawan') dan admin (role: 'admin')
- `app/Models/Inventory.php` — model barang inventaris
- `app/Models/Transaction.php` — model transaksi
- `app/Models/TransactionItem.php` — item per transaksi
- `resources/views/admin/` — semua halaman admin
- `resources/views/user/` — halaman form tiket karyawan

## Aturan Bisnis Penting
- **PIN untuk hapus massal**: `447747`
- **Role user**: `admin` = admin, `karyawan` = pegawai biasa
- **Status Inventory**: `Aktif` (sedang dipakai), `Disimpan` (tersimpan di IT), `Return Vendor`
- **Kondisi Inventory**: `Baik`, `Rusak`
- **Lokasi Inventory**: `Di MPTB` (di ruang kerja karyawan), `Ruangan IT` (tersimpan di IT)
  - ⚠️ Tidak ada lagi nilai `Diluar MPTB` sebagai lokasi — digantikan oleh flag `hak_bawa_pulang`
- **Jenis Transaksi**: `serah_terima`, `peminjaman`, `penukaran`, `pengembalian`
- **Status Tiket**: `menunggu_diisi`, `menunggu_validasi`, `selesai`

## Kolom Penting di Tabel `inventories`
- `hak_bawa_pulang` (boolean, default false) — menandai apakah barang boleh dibawa keluar MPTB
  - Di-set `true` saat peminjaman luar di-approve di `ValidasiController`
  - Di-reset `false` saat barang dikembalikan via `TransaksiController::storePengembalian`
  - Tampil sebagai badge "Ya" / "Tidak" di Master Data table
  - Bisa di-edit manual di form Edit Master Data
- `lokasi` — hanya `Di MPTB` atau `Ruangan IT`
- `alasan_penukaran`, `penjelasan_kerusakan` — ada di tabel `transaction_items`

## Konvensi Kode
- **Alpine.js**: State & UI interaction, diinisialisasi via `Alpine.data()` di `@push('scripts')`
- **Modal**: Semua modal pakai Alpine `x-show` + `x-cloak`
- **Dropdown Divisi**: IT, HR, Legal, Translator, Agent, TL, QC, SPV, Vendor
- **Dropdown Ruangan**: Ruangan 1-9, Ruangan IT, Ruangan Manajemen
- **Activity Log**: Semua aksi penting dicatat via `ActivityLog::record()`

## Fitur Soft Delete Karyawan
- Kolom `deleted_at` di tabel `users` (SoftDeletes sudah aktif di User model)
- Kolom `aset_tersimpan` (JSON) menyimpan snapshot aset sebelum karyawan dihapus
- Saat restore → aset otomatis aktif kembali + linked ke karyawan
- Retensi 30 hari (via `Prunable` di User model)
- Tab "Riwayat Dihapus" di halaman Karyawan untuk melihat dan memulihkan data

## Tiket Penukaran (user/tiket.blade.php)
- Form penukaran mendukung multi-barang sejenis dalam 1 tiket (key = SN barang, bukan kategori)
- Setiap item di-loop dari `activeSwapCategories` — objek: `{ key, label, sn_lama, alasan, penjelasan, has_foto }`
- `getCategoryKey(item)` menggunakan `item.sn.replace(/\W/g, '')` agar unik per barang
- Field yang dikirim: `items[{key}][sn_lama]`, `items[{key}][no_aset]`, `items[{key}][alasan_penukaran]`, `items[{key}][penjelasan_kerusakan]`, `items[{key}][kategori]`
- Backend `UserTicketController::submit()` membaca `items[]` secara dinamis (bukan loop KATEGORI_LIST)
- Field foto **TIDAK diwajibkan** (aturan validasi foto sudah di-comment-out)

## Dashboard Admin — Form Penukaran (admin/transaksi.blade.php)
- `penukaranApp()` function Alpine di admin/transaksi.blade.php
- Barang ditampilkan sebagai button toggle; key unik berdasarkan SN barang (`item.sn.replace(/\W/g, '')`)
- `activeSwapCategories` menyimpan barang yang dipilih: `{ key, label, sn_lama, alasan, penjelasan, has_foto }`
- Backend: `TransaksiController::storePenukaran` → `saveItemsFromCategoriesPenukaran()` loop `$items` dynamically
- `alasan_penukaran` dan `penjelasan_kerusakan` disimpan di `transaction_items`

## Tabel Penukaran di Admin Dashboard
- Kolom "Barang Masuk (Rusak)" = `$t->items->pluck('sn_lama')->filter()->implode(', ')`
- Kolom "Barang Keluar (Baru)" = `$t->items->pluck('no_aset')->filter()->implode(', ')`

## Logika Lokasi dan Hak Bawa Pulang
| Aksi | Status | Lokasi | hak_bawa_pulang |
|---|---|---|---|
| Serah Terima / Peminjaman Dalam di-approve | Aktif | Di MPTB | false |
| Peminjaman Luar di-approve | Aktif | Di MPTB | **true** |
| Retur (Kembali ke IT) | Disimpan | Ruangan IT | false |
| Retur (Tetap Pakai, Cabut Hak Bawa Pulang) | Aktif | Di MPTB | false |
| Penukaran — aset lama | Disimpan | Ruangan IT | false |
| Penukaran — aset baru ke karyawan | Aktif | Di MPTB | false |

## Peminjaman Sub-Tab (Pinjam Dalam vs Pinjam Luar)
- **Pinjam Dalam**: `item.hak_bawa_pulang === false`
- **Pinjam Luar**: `item.hak_bawa_pulang === true`
- Filter di `PageController::activeBorrowers`: menggunakan `where('hak_bawa_pulang', false/true)`
- Filter di Vue tooltip: `d.hak_bawa_pulang` / `!d.hak_bawa_pulang`

## Route Prefix
Semua route admin berada di prefix `/workspaceinventory`, misal:
- `POST /workspaceinventory/karyawan` → tambah karyawan
- `POST /workspaceinventory/karyawan/{id}/restore` → restore karyawan
- `POST /workspaceinventory/transaksi/serah-terima` → transaksi
- `POST /workspaceinventory/transaksi/penukaran` → penukaran (via TransaksiController::storePenukaran)
- `POST /workspaceinventory/validasi/{id}/approve` → approve tiket (via ValidasiController::approve)

## Lokasi Proyek
`c:\IT Inventory 2026\Development\ventorialls\ventorialls-app`
