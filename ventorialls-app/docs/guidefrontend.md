# Panduan Gaya Frontend (Frontend Style Guide)

Dokumen ini berisi panduan gaya (style guide) untuk pengembangan frontend pada proyek Ventorialls agar desain tetap konsisten, rapi, dan memiliki estetika premium modern. Semua komponen antarmuka harus mengikuti acuan kelas Tailwind CSS di bawah ini.

## 1. Warna Utama (Color Palette)
- **Primary Blue**: `#1d4ed8` (digunakan untuk action utama, border aktif, ring focus).
- **Secondary Blue**: `#3b82f6` (digunakan untuk gradien bersama primary).
- **Background Netral**: `bg-slate-50` (untuk input form) atau `bg-slate-100`.
- **Teks Utama**: `text-slate-800` (untuk judul), `text-slate-700` (untuk label teks normal).
- **Teks Sekunder**: `text-slate-500` atau `text-slate-400` (untuk label kecil, placeholder).
- **Warna Aksi Spesifik**: 
  - Emerald (`emerald-500`) untuk status aktif/sukses.
  - Amber (`amber-500`) untuk status tersedia/peringatan.
  - Red/Rose (`red-600` / `rose-600`) untuk error/rusak.

## 2. Tipografi (Typography)
- **Label Kecil (Uppercase)**: Digunakan di atas nilai statistik atau input form.
  `text-[10px]` atau `text-[11px] text-slate-500 font-bold uppercase tracking-wider`
- **Angka Statistik (Besar)**: 
  `text-2xl font-bold text-slate-800 tracking-tight`
- **Judul Card / Bagian**:
  `text-sm font-bold text-slate-800 truncate`
- **Font Input / Mono**: Digunakan untuk input nomor seri atau data teknis.
  `font-mono text-sm`

## 3. Komponen Card (Kartu)
Setiap card kontainer pada dashboard menggunakan gaya melengkung (rounded-2xl) dengan bayangan lembut.
**Kelas Tailwind:**
`bg-white rounded-2xl p-5 shadow-soft border border-slate-100`

**Efek Hover Card Interaktif:**
`hover:shadow-md transition-all cursor-pointer hover:border-emerald-200` (Warna border disesuaikan konteks).

## 4. Input Form
Input form harus terlihat bersih dengan interaksi fokus yang jelas (berpindah dari abu-abu terang ke putih dengan ring biru).
**Kelas Tailwind:**
`w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors`

## 5. Tombol (Buttons)

### A. Tombol Utama (Primary Submit Button)
Digunakan untuk aksi utama seperti "Simpan Peminjaman" atau form submit lainnya. Desainnya menggunakan gradien dengan bayangan dan perubahan warna saat hover.
**Kelas Tailwind:**
`bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] transition-colors text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm`

### B. Tombol Aksi Spesifik / Ikon (Misal: Cek Data Database, Kamera)
Digunakan untuk aksi pendukung kecil di dalam atau di sebelah form. Tampilannya netral agar tidak mengalahkan fokus ke tombol utama.
**Kelas Tailwind:**
`px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm text-xs font-bold`

### C. Tombol Ikon (Misal: Ikon Kamera)
Digunakan untuk aksi kecil berupa ikon di sebelah form.
**Kelas Tailwind:**
`px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm`

### D. Tombol Outlined (Secondary Action)
Digunakan untuk aksi netral seperti "Batal" atau "Kembali".
**Kelas Tailwind:**
`bg-white border border-slate-200 hover:border-[#1d4ed8] hover:text-[#1d4ed8] transition-colors text-slate-700 px-6 py-2.5 rounded-xl font-medium shadow-sm text-sm`

## 6. Ikon
- Ikon menggunakan [Lucide Icons](https://lucide.dev/).
- Pembungkus Ikon (Icon Wrapper) pada Card:
  `w-12 h-12 rounded-xl bg-gradient-to-br from-{color}-100 to-{color}-50 text-{color}-600 flex items-center justify-center shadow-sm border border-{color}-100`

---
*Catatan: Dokumen ini harus diperbarui jika ada komponen baru dengan gaya signifikan yang ditambahkan ke aplikasi.*
