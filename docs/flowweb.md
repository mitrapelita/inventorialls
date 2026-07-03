# Flow Aplikasi Inventaris PT MPTB

Dokumen ini berisi dokumentasi dan alur (flow) aplikasi inventaris berbasis web.

## 1. Requirement Form Transaksi

Berikut adalah daftar input data yang diwajibkan untuk masing-masing jenis transaksi:

### A. Serah Terima
- **Nama Lengkap**
- **NO WA Aktif**
- **Nama Team Leader**
- **No KTP**
- **Alamat sesuai KTP**
- **Domisili saat ini**
- **Data Aset**: Wajib isi No Aset lalu foto No Aset nya (klik langsung buka kamera) agar admin bisa memvalidasi kebenaran data yang diinput.
- **Ruangan saat ini** (Ada Ruang 1-9)
- **Catatan**: 
  - Form serah terima ini berfungsi sebagai langkah awal untuk memasukkan (entry) data user baru.
  - Format dokumen print disesuaikan dengan data di atas.

### B. Peminjaman Lingkup MPTB
- **Nama Lengkap**: Nge-load dari data user yang sudah ada. Saat nama di-search, data seperti NO WA Aktif dan Nama Team Leader akan otomatis terisi.
- **Catatan**:
  - Jika data belum lengkap, pengguna wajib melengkapi data yang kurang sebelum melanjutkan isi data aset.
  - Jika nama belum ada di database, pengguna wajib menambahkan data tersebut terlebih dahulu.
- **Data Aset**: Wajib isi No Aset lalu foto No Aset nya (klik langsung buka kamera) agar admin bisa memvalidasi.

### C. Penukaran Barang
- **Nama Lengkap**: Sama seperti peminjaman (nge-load otomatis).
- **Catatan**:
  - Jika data belum lengkap, wajib lengkapi kekurangan data baru bisa mengisi form penukaran.
  - Jika belum ada di database, wajib menambah data.
- **Data Aset**: Wajib isi No Aset lalu foto No Aset nya (klik langsung buka kamera) untuk validasi admin.

### D. Peminjaman Luar MPTB
- **Nama Lengkap**: Nge-load data user.
- **Catatan**:
  - Melengkapi kekurangan data (jika ada).
  - Tambah data jika pengguna belum terdaftar.
- **Data Aset yang dipinjam**: Wajib isi No Aset lalu foto No Aset nya (klik langsung buka kamera) untuk validasi.
- **Print Out**: Dokumen print disesuaikan dengan input (Format dokumen menyusul).

### E. Pengembalian
- **Mekanisme**: Cukup validasi admin dengan klik button yang ada di sistem (Retur/Kembalikan barang).

### F. Gate Pass
- **Rincian Barang**: Custom textarea untuk isi data barang (Contoh: Laptop 10 Unit, Kabel 10 Unit).
- **Alamat Pengirim**
- **Alamat Tujuan**
- **Tanggal Kembali**: Wajib diisi.
- **Print Out**: Wajib bisa cetak dokumen Gate Pass.

---

## 2. Alur Validasi Transaksi

1. **Staging Data**: Semua inputan transaksi dari user **TIDAK** akan langsung masuk ke Master Data.
2. **Validasi Admin**: Data tersebut akan masuk ke halaman *Validasi Inputan User* terlebih dahulu untuk diulas oleh admin.
3. **Pusat Validasi**: Terdapat menu khusus bernama **Validasi Data & Fisik** yang memiliki dua kategori/tab:
   - **Validasi Fisik & Tools**: Menggunakan scan/input barcode seperti fitur yang sudah ada sebelumnya.
   - **Validasi Inputan User**: Menampilkan antrean data transaksi masuk dari form user yang menunggu persetujuan/validasi dari Admin agar dapat resmi tercatat di sistem (Master Data).
