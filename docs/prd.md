# 📄 Product Requirements Document (PRD): Ventorialls

## 1. Product Overview
- **Product Name:** Ventorialls
- **Problem Statement:** Kesulitan dalam melakukan manajemen inventaris di internal perusahaan.
- **Goals & Objectives:** 
  - Meningkatkan efisiensi dan mempermudah proses pendataan inventaris.
  - Memperkecil kemungkinan hilangnya (kecolongan) barang inventaris.
  - Memudahkan pelacakan (*tracking*) lokasi dan status barang inventaris.
  - Memudahkan visibilitas keterkaitan antara *user* (karyawan) dengan barang yang sedang digunakannya.

## 2. Target Audience & User Roles
- **Target Audience:** Karyawan internal perusahaan.
- **User Roles & Access Levels:**
  - **Admin Inventory:** Memiliki akses penuh untuk mencatat, mengedit, dan memvalidasi serah terima barang.
  - **SPV Admin:** Melakukan *supervising* atau pengecekan tingkat lanjut terhadap data inventaris.
  - **IT Support:** Mengelola status barang terkait perangkat IT (masuk *maintenance* atau kembali dari servis).
  - **Karyawan (User Biasa):** Subjek yang menerima, meminjam, dan mengembalikan barang.

## 3. Core Features (MVP)
Aplikasi harus memiliki fungsionalitas berikut pada rilis pertama (MVP):

1. **Manajemen Serah Terima (Onboarding):**
   - Pendataan karyawan baru (*onboarding*) beserta inventaris yang dipegangnya.
   - Wajib fitur **Cetak/Print Berita Acara Serah Terima (BAST)**.
2. **Manajemen Peminjaman Tambahan:**
   - Fitur pendataan ketika *user* meminjam barang tambahan di luar perangkat standar.
3. **Manajemen Pengembalian & Penukaran:**
   - Fitur pengembalian barang (*return* / karyawan *offboarding*).
   - Fitur tukar barang (jika barang yang digunakan rusak dan butuh unit *replacement*).
   - Wajib fitur **Cetak/Print BAST** untuk pengembalian dan penukaran.
4. **Perizinan & Mobilitas (Gatepass):**
   - Fitur perizinan peminjaman bawa pulang / luar kantor.
   - Wajib fitur **Cetak Surat Izin / BAST** dibawa ke luar kantor.
5. **Pelacakan & Visibilitas:**
   - Fitur untuk melihat daftar seluruh inventaris yang sedang dipegang oleh seorang karyawan tertentu.
   - Fitur melihat **Histori Barang** (riwayat pemakaian: pernah dipakai siapa saja, kapan rusak, kapan diservis).
6. **Validasi Fisik (Stock Opname / Audit):**
   - Fitur validasi untuk pengecekan ulang kesesuaian antara data sistem dengan fisik barang yang dipinjam *user*.
7. **Barang Masuk & Keluar:**
   - **Barang Masuk:** Entri barang baru beli, atau barang yang selesai dari servis vendor.
   - **Barang Keluar:** Pendataan barang rusak, masuk *maintenance*, atau *scrap* (tidak terpakai lagi).
   - Wajib fitur **Cetak BAST**.
8. **Logging & Dashboarding:**
   - **Log Aktivitas:** Mencatat *audit trail* (siapa melakukan perubahan apa dan kapan).
   - **Dashboard Inventaris:** Ringkasan status inventaris secara *real-time*.
   - **Chart & Statistik:** Visualisasi aktivitas harian/bulanan/tahunan (grafik pekerjaan admin, rasio barang masuk/keluar) yang dapat di-*filter* berdasarkan tanggal.
9. **Manajemen Master Data:**
   - Fitur kelola Master Data (Departemen, Lokasi, Karyawan, dll).

## 4. Master Data Requirements (Berdasarkan Gambar)
Struktur data (Tabel Database) untuk Master Data Inventaris harus mencakup *field* berikut:
- **No / ID:** Identifier unik (sebaiknya di *backend* menggunakan UUID/Auto Increment).
- **Jenis Barang:** Kategori barang (Misal: Laptop, HP).
- **Merk / Tipe:** Merek dan tipe spesifik (Misal: Lenovo, Dell, HP).
- **Serial Number / Asset Tag:** Nomor seri / kode registrasi inventaris (Misal: UP-LAP-001).
- **Pengguna:** Relasi ke nama karyawan yang sedang menggunakan barang.
- **Kontak Pengguna:** Nomor HP / ekstensi kantor pengguna (bisa ditarik dari relasi data Karyawan).
- **Department:** Departemen pengguna (Misal: Agent, IT, HR, Legal, Translator, TL, QC, SPV, Vendor).
- **Tanggal Masuk:** Tanggal barang tersebut pertama kali masuk / dibeli oleh perusahaan.
- **Tanggal Sign In:** Tanggal serah terima barang kepada pengguna.
- **Kondisi:** Kondisi fisik dan fungsi barang (Misal: Baik, Rusak).
- **Status:** Status ketersediaan (Misal: Aktif, Return, Disimpan).
- **Lokasi:** Lokasi fisik penempatan barang (Misal: Ruang Kerja, Diluar MPTB, Ruangan IT).
- **Kepemilikan:** Status kepemilikan aset (Misal: Vendor, PT MPTB).
- **Keterangan:** Catatan opsional / detail kerusakan (Misal: "Baterai rusak", "Layar berkedip").

## 5. Future Features (Nice-to-Have)
- Fitur analisis menggunakan **AI (Artificial Intelligence)**, misalnya untuk memprediksi umur barang, merekomendasikan penggantian, atau mendeteksi pola kerusakan.

## 6. Tech Stack & Platform
- **Platform:** Aplikasi Berbasis Web (*Web-based Application*), dengan sifat **SANGAT RESPONSIVE** (bisa dibuka di HP maupun Desktop).
- **Tech Stack:** 
  - **Backend:** Laravel, PHP. *(Note: Laravel saat ini berada di rilis v11, kita akan menggunakan versi paling stabil/terbaru).*
  - **Database:** MySQL.
  - **Styling:** Tailwind CSS.

## 7. UI/UX & Desain
- **Gaya Visual & Konsep:** Clean, Professional, Modern, Minimalis, dengan gaya *dashboard* yang terang (*bright & airy*), sudut komponen yang membulat (*rounded corners*), dan *soft drop-shadows* untuk memberikan efek kedalaman dimensi yang *clean*.
- **Warna Utama (Primary Color):** Biru laut/Biru profesional (seperti pada gambar referensi, namun menggunakan tingkat *shade* yang sedikit lebih gelap untuk kesan yang lebih tegas dan keterbacaan yang lebih baik, contoh kode warna: `#1d4ed8` atau `#1E60CE`).
- **Warna Latar (Background):** Dominan putih bersih (`#FFFFFF`) dipadukan dengan aksen abu-abu sangat muda/kebiruan (`#F8F9FA` atau `#F4F7FB`) untuk memisahkan area kanvas (*sidebar* & *background*) dengan konten utama.
- **Ikonografi (Icons):** Direkomendasikan menggunakan *icon set* **Lucide Icons** atau **Heroicons**. Ikon harus berdesain *modern-minimalis* (berupa garis tepi/*outline* konsisten), tidak terlalu tebal, dan sudut yang membulat agar serasi dengan *vibe* keseluruhan.
- **Tipografi (Font):** Menggunakan font **Plus Jakarta Sans** secara eksklusif untuk memberikan kesan modern, rapi, elegan, dan keterbacaan yang sangat baik pada deretan data angka/huruf inventaris.
- **Standarisasi:** Desain dasar dan hirarki komponen akan distandarisasi menggunakan *guidelines* dari **UIUX Promax**.

## 8. Keamanan & Non-Fungsional
- **Keamanan:** 
  - Autentikasi dan Otorisasi per *Role*.
  - Enkripsi data inventaris sensitif.
  - **Saran Enkripsi (Encode2an):** 
    - *Hashing* menggunakan `Bcrypt` atau `Argon2` untuk *password* (bawaan Laravel).
    - Penggunaan **UUID** sebagai Primary Key untuk *routing*, agar pihak luar tidak bisa menebak ID barang (`/inventory/1` -> `/inventory/a1b2c3d4...`).
    - *Two-Way Encryption* menggunakan `Crypt` bawaan Laravel (`AES-256-CBC`) jika ada data spesifik (seperti dokumen legal kepemilikan) yang harus disandikan di database namun perlu dibaca oleh admin.
- **Timeline:** Harus rampung secepatnya, selambat-lambatnya dalam waktu **3 Bulan**.

---

## 9. Struktur File & Clean Code Architecture (Laravel)

Proyek ini akan menggunakan arsitektur **MVC** bawaan Laravel yang diperkuat dengan pola **Service Layer** dan **Repository Layer** untuk memastikan kode mudah dirawat, diperluas, dan di-*test*.

```
ventorialls/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php          # Login, Logout, profil
│   │   │   ├── Master/
│   │   │   │   ├── InventarisController.php    # CRUD master data barang
│   │   │   │   ├── KaryawanController.php      # CRUD master data karyawan
│   │   │   │   ├── DepartmentController.php    # CRUD departemen
│   │   │   │   └── LokasiController.php        # CRUD lokasi
│   │   │   ├── Transaksi/
│   │   │   │   ├── SerahTerimaController.php   # Onboarding / BAST
│   │   │   │   ├── PeminjamanController.php    # Peminjaman tambahan
│   │   │   │   ├── PengembalianController.php  # Return barang
│   │   │   │   ├── PenukaranController.php     # Tukar barang (replacement)
│   │   │   │   ├── GatepassController.php      # Izin bawa pulang
│   │   │   │   └── ValidasiController.php      # Validasi/stock opname
│   │   │   ├── Laporan/
│   │   │   │   ├── HistoriController.php       # Riwayat barang
│   │   │   │   └── StatistikController.php     # Chart & statistik
│   │   │   └── DashboardController.php         # Dashboard utama
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php              # Guard per role (admin, spv, dll)
│   │   │   └── ActivityLogMiddleware.php       # Auto-log setiap request
│   │   └── Requests/
│   │       ├── Master/
│   │       │   ├── StoreInventarisRequest.php
│   │       │   └── StoreKaryawanRequest.php
│   │       └── Transaksi/
│   │           ├── StoreSerahTerimaRequest.php
│   │           └── StorePeminjamanRequest.php
│   ├── Models/
│   │   ├── User.php                            # Akun login
│   │   ├── Inventaris.php                      # Model master barang
│   │   ├── Karyawan.php                        # Model karyawan
│   │   ├── Department.php
│   │   ├── Lokasi.php
│   │   ├── SerahTerima.php                     # Transaksi serah terima
│   │   ├── Peminjaman.php
│   │   ├── Pengembalian.php
│   │   ├── Penukaran.php
│   │   ├── Gatepass.php
│   │   ├── Validasi.php
│   │   └── ActivityLog.php                     # Log aktivitas admin
│   ├── Services/                               # Business Logic Layer
│   │   ├── InventarisService.php
│   │   ├── SerahTerimaService.php
│   │   ├── BastPdfService.php                  # Generate PDF BAST
│   │   └── ValidasiService.php
│   └── Repositories/                           # Data Access Layer
│       ├── InventarisRepository.php
│       └── KaryawanRepository.php
├── database/
│   ├── migrations/                             # Semua file migrasi tabel
│   └── seeders/
│       ├── DepartmentSeeder.php
│       └── LokasiSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php                   # Layout utama (sidebar+navbar)
│   │   │   └── auth.blade.php                  # Layout halaman login
│   │   ├── components/
│   │   │   ├── sidebar.blade.php
│   │   │   ├── navbar.blade.php
│   │   │   ├── stat-card.blade.php             # Kartu statistik dashboard
│   │   │   ├── data-table.blade.php            # Komponen tabel reusable
│   │   │   ├── modal.blade.php                 # Komponen modal reusable
│   │   │   ├── badge-status.blade.php          # Badge kondisi/status
│   │   │   └── alert.blade.php                 # Alert notifikasi (duplikat, dll)
│   │   ├── auth/
│   │   │   └── login.blade.php
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── master/
│   │   │   ├── inventaris/
│   │   │   │   ├── index.blade.php             # Tabel master inventaris
│   │   │   │   ├── create.blade.php            # Form tambah barang
│   │   │   │   └── edit.blade.php              # Form edit barang
│   │   │   ├── karyawan/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── create.blade.php
│   │   │   └── department/
│   │   │       └── index.blade.php
│   │   ├── transaksi/
│   │   │   ├── serah-terima/
│   │   │   ├── peminjaman/
│   │   │   ├── pengembalian/
│   │   │   ├── penukaran/
│   │   │   ├── gatepass/
│   │   │   └── validasi/
│   │   └── laporan/
│   │       ├── histori/
│   │       └── statistik/
│   ├── css/
│   │   └── app.css                             # Entry point Tailwind CSS
│   └── js/
│       └── app.js                              # Entry point JS (Alpine.js)
├── routes/
│   ├── web.php                                 # Semua route web (grouped by role)
│   └── api.php                                 # Route API (jika diperlukan)
├── config/
│   └── ventorialls.php                         # Konfigurasi custom (enum status, dll)
└── docs/
    ├── prd.md                                  # ← File ini
    └── advanced-vibecode-byshad.md
```

### Konvensi Penamaan (Naming Convention)
- **Controller:** `PascalCase`, suffix `Controller` (contoh: `InventarisController`)
- **Model:** `PascalCase`, singular (contoh: `Inventaris`, `Karyawan`)
- **Service:** `PascalCase`, suffix `Service` (contoh: `BastPdfService`)
- **View:** `kebab-case` (contoh: `create.blade.php`, `serah-terima/`)
- **Route:** `kebab-case` (contoh: `/master/inventaris`, `/transaksi/serah-terima`)
- **Database Table:** `snake_case`, plural (contoh: `master_inventaris`, `serah_terimas`)

