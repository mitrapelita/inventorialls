# Panduan Meng-online-kan Localhost dengan Cloudflare Tunnel (Tanpa Halaman Peringatan)

Dokumen ini berisi panduan untuk mengekspos aplikasi Laravel lokal ke internet publik menggunakan **Cloudflare Tunnel (`cloudflared`)** sebagai alternatif dari Ngrok. 
Kelebihan utama Cloudflare Tunnel (mode Quick Tunnel tanpa akun) adalah **tidak adanya halaman peringatan "Visit Site"**, sehingga user/karyawan bisa langsung masuk ke aplikasi dengan mulus.

---

## 1. Persiapan Awal
Sebelum menjalankan Cloudflare Tunnel, pastikan aplikasi Laravel sudah berjalan dan di-build dengan benar:

1. **Jalankan Server PHP:**
   Buka terminal di dalam VS Code (atau terminal terpisah) dan jalankan perintah:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8001
   ```
   *(Pastikan terminal ini tetap dibiarkan menyala).*

2. **Build Asset (Penting untuk CSS/JS):**
   Agar file CSS dan Javascript bisa dimuat dengan benar (tidak terblokir masalah Mixed-Content), kita harus mem-build file statisnya. Buka terminal baru dan jalankan:
   ```bash
   npm run build
   ```
   *(Tunggu sampai proses selesai. Anda tidak perlu menyalakan `npm run dev` saat menggunakan Cloudflare Tunnel).*

3. **Konfigurasi HTTPS di Laravel (Sudah Diterapkan):**
   Pastikan di dalam file `app/Providers/AppServiceProvider.php`, pada fungsi `boot()`, sudah terdapat kode berikut untuk memaksa penggunaan link HTTPS (mencegah error di Safari/Chrome):
   ```php
   if (str_contains(request()->getHost(), 'ngrok') || str_contains(request()->getHost(), 'trycloudflare.com')) {
       \Illuminate\Support\Facades\URL::forceScheme('https');
   }
   ```

---

## 2. Cara Download & Menjalankan Cloudflared (Windows)

Langkah-langkah untuk Windows:

1. **Download Aplikasi:**
   Download file `cloudflared` (versi `cloudflared-windows-amd64.exe`) melalui link resmi GitHub Cloudflare:
   [Link Download Cloudflared Windows](https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-windows-amd64.exe)

2. **Ubah Nama File (Opsional):**
   Setelah ter-download, ganti nama file tersebut menjadi `cloudflared.exe` agar lebih mudah diketik.

3. **Buka Terminal:**
   Buka aplikasi **Command Prompt (CMD)** baru.

4. **Jalankan Perintah dengan Drag-and-Drop:**
   - **Tarik (Drag)** file `cloudflared.exe` tadi dari foldernya ke dalam jendela CMD hitam, lalu **Lepas (Drop)**.
   - Nanti akan otomatis muncul alamat path (misalnya: `"C:\Users\NamaUser\Downloads\cloudflared.exe"`).
   - Tekan tombol **Spasi** sekali.
   - Ketik tambahan argumen ini: `tunnel --url http://localhost:8001`
   - Hasil akhir teks di CMD akan terlihat seperti ini:
     ```cmd
     "C:\Users\NamaUser\Downloads\cloudflared.exe" tunnel --url http://localhost:8001
     ```

5. **Dapatkan Link Publiknya!**
   - Tekan **Enter**.
   - Tunggu beberapa detik, CMD akan memunculkan beberapa baris log (tulisan berjalan).
   - Cari baris di bagian bawah/tengah yang terdapat link berawalan `https://` dan berakhiran `.trycloudflare.com`.
   - *Contoh: `https://kucing-makan-ikan.trycloudflare.com`*
   - Copy link tersebut dan bagikan ke user. Link tersebut sudah bisa diakses dari perangkat dan jaringan mana pun tanpa halaman peringatan!
