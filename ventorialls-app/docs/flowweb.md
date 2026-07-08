# Flow Website untuk Kebutuhan Setting Backend & Database

Dokumen ini menjelaskan alur kerja (flow) dari aplikasi web IT Inventory untuk membantu proses pengembangan backend dan database.

---

## A. Menu Transaksi

Semua inputan transaksi memiliki dua jalur masuk: dari **Dashboard Admin** atau melalui **Form Tiket User**. Oleh karena itu, skema dan field datanya harus sama persis. Semua proses di menu transaksi tidak langsung mengubah data aset utama, melainkan masuk ke **Menu Validasi** terlebih dahulu. 
- Saat data di Menu Validasi disetujui (klik valid/sesuai), barulah data masuk ke Master Database dan akan memperbarui *History Pengguna*. 
- Segala bentuk manipulasi data (penambahan, pengeditan, penghapusan) akan tercatat otomatis di **Log Aktivitas**.

### 1. Serah Terima
- **Fungsi Utama:** Selain dari Menu Karyawan, menu Serah Terima merupakan halaman awal untuk mendaftarkan user baru yang belum terdaftar di database.
- **Data Karyawan yang Diinput:** Nama Lengkap, No WA Aktif, Nama Team Leader, No KTP, Alamat Sesuai KTP, Domisili Saat Ini, dan Ruangan Saat Ini.
- **Data Aset:** Menggunakan form input berdasarkan kategori (Laptop, Charger, dll). Terdapat tombol **Cek Data** untuk memverifikasi aset, dan tombol **Kamera** untuk memotret fisik barang/nomor aset secara otomatis.
- **Proses:** Klik **Simpan Transaksi**.
- **Fitur Cetak:** Terdapat fitur cetak dokumen (Berita Acara), di mana isi dokumen harus selaras dengan data yang diinputkan.
- **Konsistensi:** Data inputan di form tiket user harus sama dengan data di menu transaksi dashboard. Tiket yang dibuat akan tampil di list/dashboard user.

### 2. Peminjaman
- **Tipe Peminjaman:** Terdiri dari 2 opsi:
  - **Internal (Lingkup MPTB):** Dapat dibuatkan tiket oleh Karyawan (Data inputan tiket harus selaras dengan dashboard) dan akan tampil di menu user.
  - **External (Luar MPTB):** Hanya bisa diproses melalui Dashboard Admin. Tersedia tombol/fungsi khusus untuk **Pengembalian**.
- **Alur Kerja:**
  1. Pilih opsi Internal atau External.
  2. Masukkan Nama Lengkap Karyawan lalu klik Cari.
     - *Jika data belum lengkap:* Sistem meminta admin untuk melengkapinya terlebih dahulu.
     - *Jika belum ada di database:* Admin harus melakukan input data karyawan baru secara manual.
  3. Isi data aset menggunakan form kategori, gunakan tombol **Cek Data**, lalu ambil foto menggunakan tombol **Kamera**.
  4. Klik **Simpan Peminjaman**.
- **Fitur Cetak:** Tersedia fitur cetak dokumen (khusus peminjaman eksternal) yang datanya mengikuti inputan.

### 3. Penukaran
- **Fungsi Utama:** Digunakan untuk proses retur atau tukar guling aset (contoh: barang rusak ditukar baru).
- **Alur Kerja:**
  1. Cari karyawan berdasarkan Nama Lengkap. Lengkapi atau tambahkan data karyawan bila belum terdaftar secara utuh.
  2. Input data aset. Di menu ini, form meminta data **Aset Lama/Rusak** dan **Aset Baru**.
  3. Gunakan tombol **Cek Data** dan tombol **Kamera** (untuk memfoto kedua aset tersebut).
  4. Klik **Proses Penukaran**.
- **Konsistensi:** Sama seperti tipe lainnya, form tiket penukaran dari user harus identik dengan dashboard dan tiket tersebut harus bisa dilihat oleh user.

---

## B. Menu Validasi

Menu ini adalah gerbang pengecekan sebelum data dieksekusi ke Master Data. Terdapat dua fungsi utama:

1. **Validasi Inputan (User & Dashboard):**
   - Transaksi yang disubmit dari Form Tiket maupun Dashboard masuk sebagai *draft* ke halaman ini.
   - Admin mengecek kesesuaian data dan dokumen. Jika dirasa sesuai, admin akan mengklik **Valid**.
   - Setelah valid, data secara resmi akan masuk ke Master Database (status kepemilikan aset berubah, status tiket berubah).
2. **Tools Validasi No. Aset:**
   - Tools pencarian untuk memverifikasi apakah suatu nomor aset sudah ada di database atau belum.
   - *Jika ada:* Akan memunculkan detail data karyawan yang saat ini memegangnya.
   - *Jika belum ada:* Akan muncul sebuah tombol/link yang mengarahkan admin untuk mengisi/mendaftarkan data aset baru tersebut.

---

## C. Log Inventaris

- Berfungsi untuk mencatat log fisik barang yang masuk atau keluar (baik itu barang baru atau barang lama/retur).
- Terdapat fitur **Print** untuk data aset yang keluar yang berfungsi sebagai lampiran Berita Acara.

---

## D. Histori Barang

- Berfungsi sebagai pelacak rekam jejak (*tracking*) sebuah alat.
- Digunakan untuk memeriksa histori pemakaian barang (menampilkan siapa saja pengguna sebelumnya hingga pengguna saat ini).

---

## E. Log Aktivitas

- Log sistem yang mencatat secara detail informasi segala perubahan, aksi, atau intervensi (*Create, Update, Delete*) yang dilakukan oleh Admin di dashboard.

---

## F. Data Karyawan (Master Karyawan)

- **Fungsi Utama:** Menampilkan daftar karyawan lengkap.
- **Operasi:** Mendukung fungsionalitas CRUD (*Create, Read, Update, Delete*) data karyawan.
- **Detail Karyawan:** Halaman detail karyawan akan otomatis menampilkan (mendisplay) daftar barang apa saja yang sedang ia pinjam/gunakan saat ini.
- **Konsistensi Struktur:** Struktur kolom input data karyawan di sini harus disamakan persis dengan data inputan awal pada saat **Serah Terima**.
- **Fitur Tambahan:** Terdapat tombol cetak/print untuk profil atau tanggungan aset karyawan tersebut.

---

## G. Status Pengerjaan & TODO (Untuk AI Berikutnya)

Bagian ini digunakan sebagai checkpoint bagi AI untuk mengetahui progres pengembangan saat ini.

**Status Selesai (DONE):**
1. **Backend Transaksi & Validasi**:
   - `TransaksiController` (storeSerahTerima, storePeminjaman, storePenukaran, storePengembalian) sudah dibangun dengan logika item dinamis (`items[kategori][no_aset]`).
   - `ValidasiController` sudah terhubung untuk mengubah status inventori ke 'Digunakan' / 'Tersedia' saat di-*approve*.
   - `ActivityLog` berjalan di semua Controller utama.
   - `SearchController` untuk pencarian Karyawan dan Inventory real-time beres.
2. **Frontend Admin Dashboard (`admin/transaksi.blade.php`)**:
   - Form **Serah Terima** telah menggunakan grid kategori dinamis dengan Alpine JS (`asetRowApp`).
   - Form **Peminjaman** telah diperbarui, menggunakan Alpine JS (`peminjamanApp`), input hidden `karyawan_id`, dan grid kategori dinamis. Sisa form lama telah dihapus.
   - Form **Penukaran** telah diperbarui, menggunakan Alpine JS (`penukaranApp`), input `sn_lama` dan `no_aset` per kategori. Sisa form lama telah dihapus.

**Belum Dijalankan (TODO):**
1. **Frontend Tiket User**: Form pada sisi tiket User (di luar admin) perlu disinkronkan persis agar name attributnya (seperti `karyawan_id`, `items[kategori][no_aset]`) sama persis dengan yang ada di `admin/transaksi.blade.php`.
2. **Validasi File / Gambar**: Memastikan upload foto di endpoint transaksi berfungsi baik dan UI menampilkan pratinjau gambar jika dibutuhkan.
3. **Menu Validasi Admin**: Update halaman Validasi di UI Admin agar ketika admin melakukan "Approve", payload yang dikirim cocok dengan apa yang dibutuhkan di backend, serta UI menampilkan data item yang terkait transaksi dengan benar.
4. **Testing E2E**: Mensimulasikan satu alur utuh dari Submit Form -> Validasi -> Cek Histori / Dashboard.
