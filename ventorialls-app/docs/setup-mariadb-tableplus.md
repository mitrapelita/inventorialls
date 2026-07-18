# Panduan Setup MariaDB & TablePlus

Dokumen ini berisi panduan langkah demi langkah untuk menginstal dan mengkonfigurasi **MariaDB** (sebagai database server) dan **TablePlus** (sebagai aplikasi pengelola database GUI) di sistem operasi Windows.

---

## 1. Instalasi dan Setup MariaDB

MariaDB adalah sistem manajemen database (RDBMS) yang cepat, stabil, dan merupakan penerus dari MySQL.

### Langkah-langkah instalasi:
1. **Download MariaDB:** 
   Buka situs resmi MariaDB di [mariadb.org/download](https://mariadb.org/download/) dan unduh versi stabil terbaru untuk Windows (biasanya berekstensi `.msi`).
2. **Jalankan Installer:**
   Buka file `.msi` yang telah diunduh, lalu klik **Next**. Centang *Terms & Conditions*, dan klik **Next** lagi.
3. **Pilih Fitur (Custom Setup):**
   Biarkan pengaturan bawaan (semua fitur akan diinstal). Klik **Next**.
4. **Pengaturan Password Root (Sangat Penting!):**
   - Centang kotak **"Modify password for database user 'root'"**.
   - Masukkan *New root password* (misalnya: `root` atau password lain yang mudah kamu ingat).
   - Masukkan ulang password di kolom *Confirm*.
   - Centang **"Enable access from remote machines for 'root' user"** jika diperlukan.
   - Klik **Next**.
5. **Database Settings:**
   - Biarkan port *default* di angka **3306**.
   - Klik **Next**, lalu klik **Install**.
6. **Selesai:** Setelah instalasi selesai, klik **Finish**. Server MariaDB sekarang sudah berjalan otomatis di belakang layar laptop/PC kamu.

---

## 2. Instalasi dan Setup TablePlus

TablePlus adalah aplikasi modern dan ringan untuk mengelola database dengan antarmuka (GUI) yang sangat nyaman.

### Langkah-langkah instalasi:
1. **Download TablePlus:**
   Buka situs resmi [tableplus.com](https://tableplus.com/) dan unduh versi Windows.
2. **Instalasi:**
   Jalankan file `.exe` yang diunduh dan ikuti langkah *Next* sampai instalasi selesai.
3. **Buka Aplikasi TablePlus.**

---

## 3. Menghubungkan TablePlus ke MariaDB

Setelah kedua aplikasi terinstal, langkah selanjutnya adalah membuat koneksi dari TablePlus ke database MariaDB kamu.

### Langkah-langkah menghubungkan:
1. Buka aplikasi **TablePlus**.
2. Klik tombol **"Create a new connection..."** (ikon plus `+` di bagian bawah atau tengah layar).
3. Pilih jenis database: **MariaDB** (jika tidak ada, pilih **MySQL**, karena MariaDB 100% kompatibel dengan MySQL).
4. Isi detail koneksi sebagai berikut:
   - **Name:** `Local MariaDB` (Bebas, hanya sebagai nama koneksi).
   - **Host:** `127.0.0.1` atau `localhost`
   - **Port:** `3306` (Biarkan *default*).
   - **User:** `root`
   - **Password:** Masukkan password root yang kamu buat saat instalasi MariaDB di atas (misal: `root`).
   - **Database:** (Kosongkan saja untuk melihat semua database).
5. Klik tombol **Test** di bagian bawah. 
   - Jika semua area menjadi warna **Hijau** (Connection OK), berarti sukses!
6. Klik **Connect** (atau **Save** jika ingin menyimpannya di halaman depan).

---

## 4. Membuat Database untuk Proyek Ventorialls

Sekarang, kita perlu membuat database kosong yang akan digunakan oleh aplikasi Laravel (Ventorialls).

1. Di dalam jendela TablePlus (setelah berhasil *Connect*), perhatikan panel sebelah kiri atau klik tombol **SQL** (ikon terminal) di menu atas.
2. Ketik perintah SQL berikut di dalam editor:
   ```sql
   CREATE DATABASE ventorialls;
   ```
3. Tekan **Ctrl + Enter** (atau tombol Run) untuk mengeksekusi perintah tersebut.
4. Refresh panel kiri, dan kamu akan melihat database bernama `ventorialls` sudah berhasil dibuat.

### Langkah Terakhir (Menyambungkan ke Laravel):
Buka file `.env` di folder utama aplikasi Ventorialls (VS Code), dan pastikan pengaturan database-nya cocok dengan yang baru saja dibuat:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ventorialls
DB_USERNAME=root
DB_PASSWORD=password_kamu_saat_instal_mariadb
```

Selesai! Aplikasi Ventorialls kamu sekarang sudah siap menggunakan database MariaDB via TablePlus secara penuh.
