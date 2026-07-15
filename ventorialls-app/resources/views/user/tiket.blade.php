<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tiket - PT MPTB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #f4f7fb;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex flex-col antialiased text-slate-800 selection:bg-indigo-100 selection:text-indigo-900" x-data="userTicketApp()">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-30">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 h-12 sm:h-16 flex items-center justify-between">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-7 h-7 sm:w-10 sm:h-10 bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] rounded-lg sm:rounded-xl flex items-center justify-center text-white shadow-sm border border-[#1d4ed8]">
                    <i data-lucide="box" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-slate-800 leading-tight">Ventorialls</h1>
                    <p class="text-[9px] sm:text-[10px] font-semibold text-slate-500 uppercase tracking-wider">User Portal</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium bg-emerald-100 text-emerald-800">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 mr-1 sm:mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Online
                </span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-3xl w-full mx-auto px-0 sm:px-6 py-4 sm:py-8">
        
        <!-- Ticket Info Card -->
        <div class="bg-white sm:rounded-2xl shadow-sm border-y sm:border border-slate-200 p-3 sm:p-6 mb-3 sm:mb-6 animate-fade-in flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="text-[15px] sm:text-xl font-bold text-slate-800 mb-0.5 sm:mb-1">
                    Form <span class="text-[#1d4ed8] capitalize">{{ $type === 'peminjaman' ? 'Peminjaman Aset' : str_replace('-', ' ', $type) }}</span>
                </h2>
                <p class="text-[11px] sm:text-sm text-slate-500 leading-relaxed">Silakan lengkapi data di bawah ini dengan sebenar-benarnya sesuai dengan aset fisik yang Anda pegang.</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 px-3 py-1.5 sm:py-2 rounded-lg text-center flex-shrink-0 flex sm:block items-center justify-between sm:justify-start">
                <p class="text-[9px] sm:text-[10px] text-blue-500 font-bold uppercase sm:mb-0.5">ID Tiket</p>
                <p class="text-xs sm:text-sm font-bold text-[#1d4ed8] font-mono">{{ $id ?? 'TKT-0000' }}</p>
            </div>
        </div>

        {{-- Success Message --}}
        @if(isset($submitted) && $submitted)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-8 text-center animate-fade-in shadow-sm">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle-2" class="w-8 h-8 text-emerald-600"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Terima Kasih!</h3>
            <p class="text-slate-600 mb-6 max-w-md mx-auto">Data tiket Anda telah berhasil dikirim dan sedang menunggu validasi oleh Admin. Anda dapat menutup halaman ini.</p>
            <button onclick="window.close()" class="px-6 py-2 sm:py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-medium transition-colors">Tutup Halaman</button>
        </div>
        @else

        {{-- Error --}}
        @if ($errors->any())
        <div class="mb-5 p-4 sm:p-5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm flex items-start shadow-sm animate-fade-in">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5 text-rose-500"></i>
            <div>
                <p class="font-bold mb-2">Harap periksa kembali form Anda:</p>
                <ul class="list-disc list-inside space-y-1 text-rose-600 text-[13px]">
                    @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- The Form --}}
        <form method="POST" action="{{ route('tiket.submit', $type) }}" enctype="multipart/form-data" @submit="validateForm" class="bg-white sm:rounded-2xl shadow-sm border-y sm:border border-slate-200 overflow-hidden animate-fade-in" style="animation-delay: 0.1s; animation-fill-mode: both;">
            @csrf
            <input type="hidden" name="ticket_code" value="{{ $id }}">
            
            <div class="p-3 sm:p-8 space-y-4 sm:space-y-8">
                
                <!-- Section 1: Data Karyawan -->
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 sm:mb-4 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-[#1d4ed8] flex items-center justify-center mr-2 text-xs">1</span>
                        Data Karyawan
                    </h3>
                    @if($type !== 'serah-terima')
                    <div class="mb-4">
                        <label class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1">Cari Data Anda (Opsional)</label>
                        <div class="flex space-x-2">
                            <input type="text" x-model="searchKaryawan" @keyup.enter.prevent="cariKaryawan()" placeholder="Ketik Nama atau WA untuk isi otomatis..." class="flex-1 px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-xs sm:text-sm transition-all">
                            <button type="button" @click="cariKaryawan()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-colors border border-slate-200">Cek data</button>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1" x-show="!karyawanFound && !showInputForm">
                            <i data-lucide="info" class="w-3 h-3 inline"></i> Jika data tidak ditemukan, silakan <button type="button" @click="showInputForm = true" class="text-[#1d4ed8] font-bold hover:underline">Isi Manual Form Karyawan</button>.
                        </p>
                    </div>

                    <!-- Read-only view when Karyawan Found -->
                    <div x-show="karyawanFound" class="mb-4 border border-emerald-100 bg-emerald-50/30 rounded-xl p-3 sm:p-4" style="display:none;" x-transition>
                        <h4 class="text-sm font-bold text-slate-800 mb-1 flex items-center">
                            <i data-lucide="user-check" class="w-4 h-4 mr-2 text-emerald-600"></i>
                            Karyawan: <span class="ml-1 font-mono text-emerald-700" x-text="karyawanData.nama"></span>
                        </h4>
                        <p class="text-xs text-slate-500">Divisi: <span x-text="karyawanData.department"></span> <span x-show="karyawanData.ruangan">| Ruangan: <span x-text="karyawanData.ruangan"></span></span></p>
                    </div>
                    @endif

                    @if($type !== 'peminjaman')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-2" 
                         @if($type !== 'serah-terima') x-show="showInputForm && !karyawanFound" style="display:none;" x-transition @endif>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500" x-show="showInputForm || !karyawanFound">*</span></label>
                            <input type="text" name="nama" x-model="karyawanData.nama" :required="showInputForm || !karyawanFound" class="w-full px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-xs sm:text-sm transition-all">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1">Divisi / Department <span class="text-rose-500" x-show="showInputForm || !karyawanFound">*</span></label>
                            <select name="department" x-model="karyawanData.department" class="w-full px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-xs sm:text-sm transition-all" :required="showInputForm || !karyawanFound">
                                <option value="">Pilih Divisi...</option>
                                <option value="IT">IT</option>
                                <option value="HR">HR</option>
                                <option value="Legal">Legal</option>
                                <option value="Translator">Translator</option>
                                <option value="Agent">Agent</option>
                                <option value="TL">TL</option>
                                <option value="QC">QC</option>
                                <option value="SPV">SPV</option>
                                <option value="Vendor">Vendor</option>
                            </select>
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1">Ruangan Saat Ini <span class="text-rose-500" x-show="showInputForm || !karyawanFound">*</span></label>
                            <select name="ruangan" x-model="karyawanData.ruangan" class="w-full px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-xs sm:text-sm transition-all" :required="showInputForm || !karyawanFound">
                                <option value="">Pilih Ruangan...</option>
                                <option value="Ruangan 1">Ruangan 1</option>
                                <option value="Ruangan 2">Ruangan 2</option>
                                <option value="Ruangan 3">Ruangan 3</option>
                                <option value="Ruangan 4">Ruangan 4</option>
                                <option value="Ruangan 5">Ruangan 5</option>
                                <option value="Ruangan 6">Ruangan 6</option>
                                <option value="Ruangan 7">Ruangan 7</option>
                                <option value="Ruangan 8">Ruangan 8</option>
                                <option value="Ruangan 9">Ruangan 9</option>
                                <option value="Ruangan IT">Ruangan IT</option>
                                <option value="Ruangan Manajemen">Ruangan Manajemen</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">No WA Aktif <span class="text-rose-500" x-show="showInputForm || !karyawanFound">*</span></label>
                            <input type="text" name="no_wa" x-model="karyawanData.no_wa" :required="showInputForm || !karyawanFound" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 sm:py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm transition-all">
                        </div>
                        
                        @if($type === 'serah-terima')
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">Nama Team Leader <span class="text-rose-500">*</span></label>
                            <select name="team_leader" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 sm:py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm transition-all cursor-pointer">
                                <option value="" disabled selected>Pilih Team Leader...</option>
                                @foreach($tls as $tl)
                                    <option value="{{ $tl->name }}">{{ $tl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">No KTP <span class="text-rose-500">*</span></label>
                            <input type="text" name="nik_ktp" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 sm:py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">Alamat Sesuai KTP <span class="text-rose-500">*</span></label>
                            <input type="text" name="alamat_ktp" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 sm:py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">Domisili Saat Ini <span class="text-rose-500">*</span></label>
                            <input type="text" name="domisili" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 sm:py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm transition-all">
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="mt-4">
                        <input type="hidden" name="nama" :value="karyawanData.nama">
                        <input type="hidden" name="department" :value="karyawanData.department">
                        <input type="hidden" name="ruangan" :value="karyawanData.ruangan">
                        <input type="hidden" name="no_wa" :value="karyawanData.no_wa">
                    </div>
                    @endif
                        
                    @if($type === 'peminjaman')
                    <div class="sm:col-span-2 mt-3 sm:mt-4 p-3 sm:p-4 bg-amber-50 border border-amber-100 rounded-lg sm:rounded-xl flex items-center justify-between gap-2">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-amber-900 mb-0.5 sm:mb-1">Tipe Peminjaman</label>
                            <p class="text-[10px] sm:text-xs text-amber-700 leading-tight">Tipe ini telah diatur oleh Admin.</p>
                        </div>
                        <div class="px-2.5 sm:px-4 py-1.5 sm:py-2.5 rounded-md sm:rounded-lg font-bold text-[11px] sm:text-sm bg-white text-amber-700 shadow-sm border border-amber-200 whitespace-nowrap">
                            {{ (isset($ticket) && $ticket->borrow_type === 'luar') ? 'Pinjam Luar' : 'Pinjam Dalam' }}
                        </div>
                    </div>
                    @if(isset($ticket))
                        @if($ticket->borrow_type === 'dalam')
                        <div class="bg-blue-50/50 p-3 rounded-lg text-xs text-blue-700 mt-3 border border-blue-100 flex items-start">
                            <i data-lucide="info" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i> Alat hanya digunakan di dalam lingkungan kantor PTMPTB.
                        </div>
                        @elseif($ticket->borrow_type === 'luar')
                        <div class="bg-indigo-50/50 p-3 rounded-lg text-xs text-indigo-700 mt-3 border border-indigo-100 flex items-start">
                            <i data-lucide="alert-triangle" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i> Peringatan: Alat dibawa keluar PTMPTB. BAST Ekstra akan dicetak.
                        </div>
                        @endif
                    @endif
                    @endif
                </div>

                <hr class="border-slate-100">

                <!-- Section 2: Data Aset -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-[#1d4ed8] flex items-center justify-center mr-2 text-xs">2</span>
                        Data Aset & Bukti Fisik
                    </h3>
                    
                    @if($type === 'penukaran')
                    <!-- Pilihan Penukaran Karyawan (Dinamis) -->
                    <div class="mb-3 sm:mb-4" x-show="karyawanData.nama !== ''" style="display:none;">
                        <label class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-2">Pilih Aset yang Akan Ditukar:</label>
                        
                        <template x-if="karyawanItems.length === 0">
                            <div class="text-[11px] sm:text-sm text-rose-600 bg-rose-50 p-3 sm:p-4 rounded-lg sm:rounded-xl border border-rose-100 flex items-start">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5 text-rose-500"></i>
                                <span>Anda belum memiliki aset aktif yang bisa ditukar.</span>
                            </div>
                        </template>

                        <div class="flex flex-wrap gap-2" x-show="karyawanItems.length > 0">
                            <template x-for="item in karyawanItems" :key="item.id">
                                <button type="button" @click="toggleSwapCategory(item)" 
                                    class="px-2.5 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold border transition-colors flex items-center"
                                    :class="isSwapCategoryActive(getCategoryKey(item)) ? 'bg-blue-600 text-white border-blue-600 shadow-sm sm:shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                                    <i data-lucide="check-circle" class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" x-show="isSwapCategoryActive(getCategoryKey(item))"></i>
                                    <span x-text="item.jenis + ' (' + item.sn + ')'"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Form Penukaran Dinamis -->
                    <div x-show="activeSwapCategories.length > 0" class="space-y-4" style="display:none;">
                        <div class="flex gap-3 text-[11px] font-bold uppercase mb-2">
                            <span class="bg-rose-50 text-rose-500 border border-rose-200 px-2 py-0.5 rounded">Merah = Aset Lama</span>
                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded">Hijau = Aset Baru</span>
                        </div>
                        
                        <template x-for="cat in activeSwapCategories" :key="cat.key">
                            <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100 relative">
                                <button type="button" @click="activeSwapCategories = activeSwapCategories.filter(c => c.key !== cat.key)" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                                <label class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1" x-text="'Penukaran ' + cat.label"></label>
                                <input type="hidden" :name="'items[' + cat.key + '][kategori]'" :value="cat.label">
                                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                        <div class="flex-1 flex gap-2">
                                            <input type="text" :name="'items[' + cat.key + '][sn_lama]'" :value="cat.sn_lama" readonly
                                                class="w-1/2 px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl border border-rose-200 bg-rose-50/50 text-rose-700 text-xs sm:text-sm outline-none cursor-not-allowed" title="Aset Lama (Otomatis)">
                                            <input type="text" :name="'items[' + cat.key + '][no_aset]'"
                                                placeholder="No. Aset Baru..." required
                                                class="w-1/2 px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl border border-emerald-200 bg-white text-xs sm:text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-sm transition-all">
                                        </div>
                                        <div class="relative shrink-0 flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                                            <input type="file" :name="'items[' + cat.key + '][foto]'" accept="image/*" capture="environment"
                                                class="opacity-0 absolute -z-10 w-0 h-0" :id="'cam-pn-' + cat.key" @change="cat.has_foto = true">
                                            <button type="button" @click="document.getElementById('cam-pn-' + cat.key).click()"
                                                :class="cat.has_foto ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-700'"
                                                title="Foto Aset Baru (WAJIB)"
                                                class="w-full sm:w-auto px-4 py-2 sm:py-2.5 hover:bg-slate-100 rounded-xl transition-colors flex items-center justify-center shadow-sm border text-xs font-semibold">
                                                <i data-lucide="camera" class="w-4 h-4 mr-2" x-show="!cat.has_foto"></i>
                                                <i data-lucide="check-circle-2" class="w-4 h-4 mr-2" x-show="cat.has_foto"></i>
                                                <span x-text="cat.has_foto ? 'Foto Tersimpan' : 'Foto Aset'"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Alasan Penukaran -->
                                    <div class="mt-2 pt-2 border-t border-slate-100 flex flex-col sm:flex-row gap-2">
                                        <select :name="'items[' + cat.key + '][alasan_penukaran]'" x-model="cat.alasan" class="w-full sm:w-1/3 px-3 py-2 rounded-xl border border-slate-200 bg-white font-medium text-xs outline-none focus:ring-2 focus:ring-[#1d4ed8]/20">
                                            <option value="rusak">Tukar karena Rusak</option>
                                            <option value="tukar_biasa">Tukar Biasa</option>
                                        </select>
                                        <div class="w-full sm:w-2/3" x-show="cat.alasan === 'rusak'">
                                            <input type="text" :name="'items[' + cat.key + '][penjelasan_kerusakan]'" x-model="cat.penjelasan" placeholder="Jelaskan detail kerusakannya..." class="w-full px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-medium text-xs text-rose-700 outline-none focus:ring-2 focus:ring-rose-500/20">
                                        </div>
                                    </div>
                            </div>
                        </template>
                    </div>

                    @else
                    {{-- Fixed Item List Categories untuk non-penukaran --}}
                    @if($type === 'peminjaman')
                        <!-- List Barang Dipinjam -->
                        <div x-show="karyawanFound" class="mb-4 border border-emerald-100 bg-emerald-50/30 rounded-xl p-4" style="display:none;">
                            <div x-show="karyawanItems.length > 0" class="mb-3">
                                <p class="text-xs font-semibold text-slate-700 mb-2">Aset yang sedang dipinjam oleh <span class="font-bold text-emerald-700" x-text="karyawanData.nama"></span>:</p>
                                <div class="space-y-1.5 mb-3">
                                    <template x-for="item in karyawanItems" :key="item.sn || item.id">
                                        <div class="flex items-center text-xs bg-white border border-emerald-100 p-2 rounded-lg">
                                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500 mr-2"></i>
                                            <span class="font-semibold text-slate-700 mr-1" x-text="item.jenis + ':'"></span>
                                            <span class="font-mono text-[#1d4ed8]" x-text="item.sn || item.merk"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div x-show="karyawanItems.length === 0" class="mb-4 bg-slate-50 border border-slate-200 p-3 sm:p-4 rounded-xl flex items-start">
                                <i data-lucide="info" class="w-4 h-4 text-slate-400 mr-2 flex-shrink-0 mt-0.5"></i>
                                <p class="text-xs sm:text-sm text-slate-500 italic">Belum ada aset yang dipinjam saat ini.</p>
                            </div>

                            <button type="button" x-show="!showInputForm" @click="showInputForm = true" class="w-full py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-bold text-xs rounded-xl transition-colors flex items-center justify-center">
                                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Pinjaman Baru
                            </button>
                        </div>
                    @endif

                    @if($type === 'peminjaman')
                        @if(isset($ticket) && $ticket->borrow_type === 'luar')
                        <!-- Peminjaman Eksternal (Luar) -->
                        <div x-show="karyawanFound && showInputForm" style="display:none;" x-transition class="space-y-4 border border-slate-200 bg-slate-50 p-4 rounded-xl">
                            <p class="text-xs text-slate-500 mb-2">Centang aset yang akan dibawa keluar (hanya menampilkan aset yang sedang dipinjam):</p>
                            
                            <div class="space-y-2 mb-4">
                                <template x-for="item in karyawanItems" :key="item.sn || item.id">
                                    <label class="flex items-center space-x-3 p-3 border border-slate-200 bg-white rounded-xl cursor-pointer hover:border-[#1d4ed8] transition-colors">
                                        <input type="checkbox" name="luar_items[]" :value="item.sn || item.merk" class="w-4 h-4 text-[#1d4ed8] border-slate-300 rounded focus:ring-[#1d4ed8]">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700" x-text="item.jenis"></span>
                                            <span class="text-xs font-mono text-[#1d4ed8]" x-text="item.sn || item.merk"></span>
                                        </div>
                                    </label>
                                </template>
                                <div x-show="karyawanItems.length === 0" class="p-3 bg-rose-50 text-rose-600 text-xs rounded-xl border border-rose-100">
                                    Anda belum meminjam aset apapun. Tidak ada aset yang bisa dibawa keluar.
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-200">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama SPV (Atasan) <span class="text-rose-500">*</span></label>
                                    <select name="spv_name" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] cursor-pointer" required>
                                        <option value="" disabled selected>Pilih SPV...</option>
                                        @foreach($approvers->where('role', 'SPV') as $spv)
                                            <option value="{{ $spv->name }}">{{ $spv->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama HRD <span class="text-rose-500">*</span></label>
                                    <select name="hrd_name" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] cursor-pointer" required>
                                        <option value="" disabled selected>Pilih HRD...</option>
                                        @foreach($approvers->where('role', 'HRD') as $hrd)
                                            <option value="{{ $hrd->name }}">{{ $hrd->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Peminjaman Internal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="items-container" x-show="karyawanFound && showInputForm" style="display:none;" x-transition>
                            @php
                                $kategoriList = [
                                    'laptop' => 'Laptop',
                                    'charger' => 'Charger',
                                    'mouse' => 'Mouse',
                                    'lan_extender' => 'LAN Extender',
                                    'headset' => 'Headset',
                                    'hp_root' => 'HP Root',
                                    'audio_jack' => 'Audio Jack'
                                ];
                            @endphp
                            @foreach($kategoriList as $key => $label)
                            <div class="flex flex-col">
                                <label class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1">{{ $label }}</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="items[{{ $key }}][no_aset]"
                                        placeholder="Input No. Aset {{ $label }}..."
                                        class="w-full px-3 sm:px-4 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-all">
                                    <!-- Hidden foto input, dipicu kamera -->
                                    <input type="file" name="items[{{ $key }}][foto]" accept="image/*" capture="environment"
                                        class="hidden" id="cam-tkt-{{ $key }}">
                                    <button type="button" onclick="document.getElementById('cam-tkt-{{ $key }}').click()"
                                        title="Foto No. Aset"
                                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm border border-slate-200">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @endif
                    @endif
                </div>
                
            </div>
            
            {{-- Footer Action --}}
            <div class="bg-slate-50 px-6 sm:px-8 py-5 border-t border-slate-200 flex justify-end" @if($type === 'peminjaman') x-show="karyawanFound && showInputForm" style="display:none;" x-transition @endif>
                <button type="submit" class="w-full sm:w-auto px-6 py-2 sm:py-2.5 bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white font-semibold rounded-xl shadow-sm border border-[#1d4ed8] transition-all flex items-center justify-center">
                    <i data-lucide="send" class="w-4 h-4 mr-2"></i> Kirim Data
                </button>
            </div>
        </form>
        @endif
        
        <footer class="mt-8 text-center pb-8 text-xs text-slate-400">
            &copy; 2026 PT MPTB - IT Inventory Management System
        </footer>

    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userTicketApp', () => ({
                searchKaryawan: '',
                karyawanData: {
                    nama: '{{ old('nama') }}',
                    department: '{{ old('department') }}',
                    ruangan: '{{ old('ruangan') }}',
                    no_wa: '{{ old('no_wa') }}'
                },
                karyawanItems: [],
                activeSwapCategories: [],
                isSearching: false,
                karyawanFound: false,
                showInputForm: false,
                
                getCategoryKey(item) {
                    if (item.sn) return item.sn.replace(/\W/g, ''); // Unique based on SN
                    return item.jenis.toLowerCase().replace(/\s+/g, '_');
                },

                toggleSwapCategory(item) {
                    const key = this.getCategoryKey(item);
                    const index = this.activeSwapCategories.findIndex(i => i.key === key);
                    if (index > -1) {
                        this.activeSwapCategories.splice(index, 1);
                    } else {
                        this.activeSwapCategories.push({
                            key: key,
                            label: item.jenis,
                            sn_lama: item.sn,
                            alasan: 'rusak',
                            penjelasan: '',
                            has_foto: false
                        });
                        setTimeout(() => lucide.createIcons(), 10);
                    }
                },

                isSwapCategoryActive(key) {
                    return this.activeSwapCategories.some(i => i.key === key);
                },

                async cariKaryawan() {
                    if(!this.searchKaryawan) return;
                    this.isSearching = true;
                    try {
                        const response = await fetch(`/workspaceinventory/api/search/karyawan?q=${encodeURIComponent(this.searchKaryawan)}`);
                        const result = await response.json();
                        if(result.found) {
                            this.karyawanData.nama = result.data.nama;
                            this.karyawanData.no_wa = result.data.kontak;
                            this.karyawanData.department = result.data.department;
                            this.karyawanData.ruangan = result.data.ruangan || '';
                            this.karyawanItems = result.data.items || [];
                            this.activeSwapCategories = [];
                            this.karyawanFound = true;
                            this.showInputForm = false;
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: 'Data ditemukan & diisi otomatis'
                            });
                        } else {
                            this.karyawanItems = [];
                            this.activeSwapCategories = [];
                            this.karyawanFound = false;
                            this.showInputForm = true;
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 4000,
                                icon: 'info',
                                title: 'Data tidak ditemukan, silakan isi manual'
                            });
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'error',
                            title: 'Terjadi kesalahan sistem'
                        });
                    }
                    this.isSearching = false;
                },
                
                validateForm(e) {
                    @if($type === 'penukaran')
                    if (this.activeSwapCategories.length === 0) {
                        e.preventDefault();
                        Swal.fire('Peringatan', 'Pilih minimal 1 aset yang ingin ditukar.', 'warning');
                        return;
                    }

                    for (const cat of this.activeSwapCategories) {
                        /*
                        const fileInput = document.getElementById('cam-pn-' + cat.key);
                        if (!fileInput || fileInput.files.length === 0) {
                            e.preventDefault();
                            Swal.fire('Foto Wajib Diisi', `Anda belum melampirkan foto untuk aset ${cat.label} (${cat.sn_lama}). Silakan klik tombol 'Foto Aset'.`, 'warning');
                            return;
                        }
                        */
                        
                        if (cat.alasan === 'rusak' && !cat.penjelasan.trim()) {
                            e.preventDefault();
                            Swal.fire('Penjelasan Wajib Diisi', `Harap isi penjelasan detail kerusakan untuk aset ${cat.label}.`, 'warning');
                            return;
                        }
                    }
                    @endif
                    
                    @if($type === 'peminjaman' && isset($ticket) && $ticket->borrow_type === 'luar')
                    const checkboxes = document.querySelectorAll('input[name="luar_items[]"]:checked');
                    if (checkboxes.length === 0) {
                         e.preventDefault();
                         Swal.fire('Peringatan', 'Pilih minimal 1 aset yang akan dibawa keluar.', 'warning');
                         return;
                    }
                    @endif
                }
            }));


        });
        lucide.createIcons();
    </script>
</body>
</html>
