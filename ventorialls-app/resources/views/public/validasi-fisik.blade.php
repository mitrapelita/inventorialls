<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Fisik & Tools - Ventorialls</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f4f7fb] text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center py-10 px-4">
    
    @if(session('success'))
        <div class="mb-6 max-w-4xl w-full bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="mb-8 text-center w-full max-w-4xl">
        <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-sm mb-4">
            <img src="{{ asset('image/logo-ventorialls-2.png') }}" alt="Ventorialls Logo" class="h-10 w-auto object-contain">
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Pengecekan Inventaris</h1>
        <p class="text-slate-500 text-sm mt-1">Sistem Validasi Fisik & Tools Publik</p>
    </div>

    <div x-data="validasiApp()" class="w-full max-w-4xl">
        <div style="display:block">
            <!-- Tab 2: Validasi Fisik & Tools -->
      <div  class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 min-w-0">
        <!-- Input Panel -->
        <div class="col-span-1 lg:col-span-1 min-w-0">
          <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5 border border-slate-100">
            <h4 class="text-sm md:text-base font-bold text-slate-700 mb-4 flex items-center">
              <i data-lucide="keyboard" class="w-4 h-4 mr-2 text-[#1d4ed8]"></i>
              Input Barang
            </h4>
            
            <form @submit.prevent="checkAsset" class="space-y-4">
              <div>
                <label class="block text-[10px] md:text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Input No. Aset</label>
                <div class="relative flex items-center">
                  <i data-lucide="keyboard" class="absolute left-3 w-4 h-4 text-slate-400"></i>
                  <input type="text" x-model="searchSn" placeholder="Input No. Aset disini..." class="w-full pl-9 md:pl-10 pr-12 md:pr-14 py-2.5 md:py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] font-mono outline-none transition-all">
                  <button type="submit" class="absolute right-2 p-1.5 md:p-2 bg-[#1d4ed8] text-white rounded-lg hover:bg-[#1e40af] transition-colors shadow-sm cursor-pointer">
                      <i data-lucide="search" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                  </button>
                </div>
                <p class="text-[9px] md:text-[10px] text-slate-400 mt-2 font-medium">Tekan Enter atau klik ikon Search untuk memvalidasi</p>
              </div>
            </form>
          </div>
        </div>

        <!-- Result Panel -->
        <div class="col-span-1 lg:col-span-2 min-w-0">
          <!-- Empty State -->
          <div x-show="!assetFound && !searched" class="bg-slate-100 rounded-2xl border border-slate-200 border-dashed h-full min-h-[250px] md:min-h-[300px] flex flex-col items-center justify-center text-slate-400 p-4 text-center">
            <i data-lucide="box" class="w-12 h-12 md:w-16 md:h-16 mb-3 md:mb-4 text-slate-300"></i>
            <p class="text-xs md:text-sm">Masukkan No. Aset untuk melihat detail barang.</p>
          </div>
          
          <!-- Not Found -->
          <div x-show="!assetFound && searched" class="bg-red-50 rounded-2xl border border-red-200 h-full min-h-[250px] md:min-h-[300px] flex flex-col items-center justify-center text-red-500 p-4 text-center" style="display: none;">
            <i data-lucide="x-circle" class="w-12 h-12 md:w-16 md:h-16 mb-3 md:mb-4 text-red-400"></i>
            <h3 class="text-base md:text-lg font-bold">Barang Tidak Ditemukan!</h3>
            <p class="text-xs md:text-sm mt-1 md:mt-2">Pastikan No. Aset diketik dengan benar.</p>
            <button type="button" @click="openLengkapiModal()" class="mt-4 px-4 md:px-6 py-2 md:py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl shadow-soft font-bold transition-all flex items-center text-xs md:text-sm">
                <i data-lucide="plus-circle" class="w-4 h-4 md:w-5 md:h-5 mr-1.5 md:mr-2"></i>
                Lengkapi Data
            </button>
          </div>

          <!-- Found Result -->
          <div x-show="assetFound" class="bg-white rounded-2xl shadow-soft p-5 border border-emerald-100 relative overflow-hidden" style="display: none;">
            <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
            
            <div class="flex justify-between items-start mb-5">
              <div>
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[10px] font-bold uppercase tracking-wide border border-emerald-200 mb-2 inline-block">Valid / Ditemukan</span>
                <h3 class="text-lg font-bold text-slate-800" x-text="assetData.merk"></h3>
                <p class="text-slate-500 font-mono text-xs" x-text="assetData.sn"></p>
              </div>
              <i data-lucide="check-circle-2" class="w-10 h-10 text-emerald-500 opacity-20"></i>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 rounded-xl p-4 mb-5 border border-slate-100">
              <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Pengguna Saat Ini</p>
                <p class="text-slate-700 text-sm font-bold" x-text="assetData.pengguna"></p>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Department</p>
                <p class="text-slate-700 text-sm font-bold" x-text="assetData.department"></p>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Kondisi</p>
                <div class="flex items-center text-sm font-bold text-slate-700">
                  <span class="w-1.5 h-1.5 rounded-full mr-2" :class="assetData.kondisi === 'Baik' ? 'bg-emerald-500' : 'bg-red-500'"></span>
                  <span x-text="assetData.kondisi"></span>
                </div>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Status</p>
                <p class="text-slate-700 text-sm font-bold" x-text="assetData.status"></p>
              </div>
            </div>
            
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
              <button class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2.5 rounded-xl transition-colors shadow-soft shadow-emerald-500/30">
                Tandai Sudah Divalidasi
              </button>
              <button class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium py-2.5 rounded-xl transition-colors">
                Update Kondisi Barang
              </button>
            </div>
          </div>
        </div>
      </div>

    
        </div>
        <!-- MODAL LENGKAPI DATA -->
    <div x-show="showLengkapiModal" 
         @click="showLengkapiModal = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl flex flex-col max-h-[90vh] overflow-hidden"
             @click.stop>
            <div class="px-4 py-3 md:px-6 md:py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                <div>
                    <h3 class="text-base md:text-lg font-bold text-slate-800 flex items-center">
                        <i data-lucide="clipboard-edit" class="w-4 h-4 md:w-5 md:h-5 mr-2 text-[#1d4ed8]"></i>
                        Lengkapi Data (Registrasi Cepat)
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Lengkapi data diri pengguna dan data barang untuk SN: <span class="font-bold text-slate-700" x-text="formData.sn"></span></p>
                </div>
                <button @click="showLengkapiModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-2 bg-white rounded-full border border-slate-200 hover:bg-slate-50">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <div class="p-4 md:p-6 overflow-y-auto flex-1">
                <form action="{{ route('public.validasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 md:space-y-6">
                    @csrf
                    
                    <div class="mb-4 bg-slate-50 p-3 md:p-4 rounded-xl border border-slate-200">
                        <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-2">Cari Nama Karyawan</label>
                        <div class="flex space-x-2">
                            <input type="text" x-model="searchKaryawanName" @keyup.enter.prevent="cariKaryawan()" @keydown.enter.prevent placeholder="Cari berdasarkan nama/WA..." class="flex-1 px-3 py-2 rounded-lg border border-slate-200 focus:border-[#1d4ed8] focus:ring-1 focus:ring-[#1d4ed8] outline-none text-xs md:text-sm">
                            <button type="button" @click="cariKaryawan()" class="bg-[#1d4ed8] hover:bg-[#1e40af] text-white px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm font-medium transition-colors flex items-center justify-center">
                                <i data-lucide="search" class="w-3.5 h-3.5 md:w-4 md:h-4 mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Cari</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Karyawan Found State -->
                    <div x-show="karyawanFound && !isKaryawanBaru" class="mb-6 border border-emerald-100 bg-emerald-50/30 rounded-xl p-4 animate-fade-in" style="display: none;">
                        <h4 class="text-sm font-bold text-slate-800 mb-2 flex items-center">
                            <i data-lucide="user-check" class="w-4 h-4 mr-2 text-emerald-600"></i>
                            Karyawan Ditemukan: <span class="ml-1" x-text="formData.pengguna"></span>
                        </h4>
                        <p class="text-xs text-slate-600">Data diri sudah terdaftar di sistem. Anda hanya perlu melengkapi Data Barang di bawah.</p>
                        
                        <!-- Hidden inputs to pass data when user is found -->
                        <input type="hidden" name="karyawan[nama]" :value="formData.pengguna">
                        <input type="hidden" name="karyawan[no_wa]" :value="formData.kontak">
                        <input type="hidden" name="karyawan[department]" :value="formData.department">
                        <input type="hidden" name="karyawan[team_leader]" :value="formData.nama_tl">
                        <input type="hidden" name="karyawan[nik_ktp]" :value="formData.no_ktp">
                        <input type="hidden" name="karyawan[alamat_ktp]" :value="formData.alamat_ktp">
                        <input type="hidden" name="karyawan[domisili]" :value="formData.domisili">
                        <input type="hidden" name="karyawan[ruangan]" :value="formData.ruangan">
                    </div>
                    
                    <!-- Section: Data Diri (Shown if Not Found or Baru) -->
                    <div x-show="karyawanFound && isKaryawanBaru" style="display: none;" class="animate-fade-in">
                        <div class="bg-amber-50 border border-amber-200 p-3 rounded-lg mb-4 flex items-start">
                            <i data-lucide="user-plus" class="w-4 h-4 text-amber-600 mr-2 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="text-xs font-bold text-amber-800">Karyawan Belum Terdaftar</h4>
                                <p class="text-xs text-amber-700 mt-1">Silakan lengkapi data diri di bawah ini untuk didaftarkan ke sistem.</p>
                            </div>
                        </div>
                        <h4 class="text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-4 border-b pb-2">1. Data Diri Pengguna</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="karyawan[nama]" x-model="formData.pengguna" :required="isKaryawanBaru" placeholder="Nama Lengkap..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">No WA Aktif</label>
                                <input type="text" name="karyawan[no_wa]" x-model="formData.kontak" :required="isKaryawanBaru" placeholder="08..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Team Leader</label>
                                <input type="text" name="karyawan[team_leader]" x-model="formData.nama_tl" :required="isKaryawanBaru" placeholder="Team Leader..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">No KTP</label>
                                <input type="text" name="karyawan[nik_ktp]" x-model="formData.no_ktp" :required="isKaryawanBaru" placeholder="NIK KTP..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Sesuai KTP</label>
                                <input type="text" name="karyawan[alamat_ktp]" x-model="formData.alamat_ktp" :required="isKaryawanBaru" placeholder="Alamat lengkap KTP..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Domisili Saat Ini</label>
                                <input type="text" name="karyawan[domisili]" x-model="formData.domisili" :required="isKaryawanBaru" placeholder="Alamat domisili..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Divisi / Department</label>
                                <select name="karyawan[department]" x-model="formData.department" :required="isKaryawanBaru" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
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
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Ruangan Saat Ini</label>
                                <select name="karyawan[ruangan]" x-model="formData.ruangan" :required="isKaryawanBaru" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
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
                        </div>
                    </div>

                    <!-- Section: Data Barang -->
                    <div x-show="karyawanFound" style="display: none;" class="animate-fade-in">
                        <label class="block text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-4 border-b pb-2">
                            <span>2. Data Aset & Bukti Fisik</span>
                        </label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $stKategori = [
                                    'laptop'       => 'Laptop',
                                    'charger'      => 'Charger',
                                    'mouse'        => 'Mouse',
                                    'lan_extender' => 'LAN Extender',
                                    'headset'      => 'Headset',
                                    'hp_root'      => 'HP Root',
                                    'audio_jack'   => 'Audio Jack',
                                ];
                            @endphp
                            @foreach($stKategori as $key => $label)
                            <div class="flex flex-col">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">{{ $label }}</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="items[{{ $key }}][no_aset]" x-model="formData.items.{{ $key }}.no_aset"
                                        placeholder="Input No. Aset {{ $label }}..."
                                        :readonly="formData.items.{{ $key }}.disabled"
                                        :class="formData.items.{{ $key }}.disabled ? 'bg-slate-200 cursor-not-allowed opacity-70' : 'bg-slate-50 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white'"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm outline-none transition-all">
                                    <!-- Hidden foto input -->
                                    <input type="file" name="items[{{ $key }}][foto]" accept="image/*" capture="environment"
                                        class="hidden" id="cam-st-{{ $key }}" :disabled="formData.items.{{ $key }}.disabled">
                                    <button type="button" onclick="document.getElementById('cam-st-{{ $key }}').click()"
                                        title="Foto No. Aset"
                                        x-show="!formData.items.{{ $key }}.disabled"
                                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm border border-slate-200">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100" x-show="karyawanFound" style="display: none;">
                        <button type="button" @click="showLengkapiModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#1d4ed8] to-[#3b82f6] text-white font-medium hover:from-[#1e40af] hover:to-[#2563eb] shadow-soft shadow-blue-500/30 transition-all flex items-center">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Simpan & Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    </div>

    <script>
        function validasiApp() {
        return {
          activeTab: 'inputan',
          showDetailModal: false,
          selectedTx: null,
          searchSn: '',
          formData: {
              sn: '',
              pengguna: '',
              kontak: '',
              department: '',
              nama_tl: '',
              no_ktp: '',
              alamat_ktp: '',
              domisili: '',
              ruangan: '',
              items: {
                  laptop: { no_aset: '', disabled: false },
                  charger: { no_aset: '', disabled: false },
                  mouse: { no_aset: '', disabled: false },
                  lan_extender: { no_aset: '', disabled: false },
                  headset: { no_aset: '', disabled: false },
                  hp_root: { no_aset: '', disabled: false },
                  audio_jack: { no_aset: '', disabled: false },
              }
          },
          searchKaryawanName: '',
          karyawanFound: false,
          isKaryawanBaru: false,
          showLengkapiModal: false,
          searched: false,
          assetFound: false,
          assetData: {},
          checkedStatus: {},
          openConfirmModal: false,
          confirmType: null,
          pendingForm: null,
          triggerConfirm(type, formEl) {
              this.confirmType = type;
              this.pendingForm = formEl;
              this.openConfirmModal = true;
              this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
          },
          submitPending() {
              if(this.pendingForm) {
                  this.pendingForm.submit();
              }
          },
          openModal(item) {
              this.selectedTx = item;
              this.checkedStatus = {}; // reset
              this.showDetailModal = true;
              this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
          },
            async cariKaryawan() {
                if(!this.searchKaryawanName) return;
                try {
                    const response = await fetch(`/workspaceinventory/api/search/karyawan?q=${encodeURIComponent(this.searchKaryawanName)}`);
                    const result = await response.json();
                    
                    this.karyawanFound = true;
                    
                    if(result.found) {
                        this.isKaryawanBaru = false;
                        this.formData.pengguna = result.data.nama;
                        this.formData.kontak = result.data.kontak || '';
                        this.formData.department = result.data.department || '';
                        this.formData.nama_tl = result.data.nama_tl || '';
                        this.formData.no_ktp = result.data.no_ktp || '';
                        this.formData.alamat_ktp = result.data.alamat_ktp || '';
                        this.formData.domisili = result.data.domisili || '';
                        this.formData.ruangan = result.data.ruangan || '';
                        
                        // Reset all items first
                        Object.keys(this.formData.items).forEach(key => {
                            this.formData.items[key].no_aset = '';
                            this.formData.items[key].disabled = false;
                        });
                        
                        // Populate existing items
                        if (result.data.items && result.data.items.length > 0) {
                            result.data.items.forEach(item => {
                                let key = '';
                                const jenis = (item.jenis || '').toLowerCase();
                                if (jenis.includes('laptop')) key = 'laptop';
                                else if (jenis.includes('charger')) key = 'charger';
                                else if (jenis.includes('mouse')) key = 'mouse';
                                else if (jenis.includes('lan')) key = 'lan_extender';
                                else if (jenis.includes('headset')) key = 'headset';
                                else if (jenis.includes('hp')) key = 'hp_root';
                                else if (jenis.includes('audio') || jenis.includes('jack')) key = 'audio_jack';
                                
                                if (key && this.formData.items[key]) {
                                    this.formData.items[key].no_aset = item.sn || item.no_aset;
                                    this.formData.items[key].disabled = true;
                                }
                            });
                        }
                    } else {
                        this.isKaryawanBaru = true;
                        this.formData.pengguna = this.searchKaryawanName;
                        this.formData.kontak = '';
                        this.formData.department = '';
                        this.formData.nama_tl = '';
                        this.formData.no_ktp = '';
                        this.formData.alamat_ktp = '';
                        this.formData.domisili = '';
                        this.formData.ruangan = '';
                        
                        // Reset all items
                        Object.keys(this.formData.items).forEach(key => {
                            this.formData.items[key].no_aset = '';
                            this.formData.items[key].disabled = false;
                        });
                    }
                    
                    this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                } catch(e) {
                    console.error(e);
                }
            },
          openLengkapiModal() {
              this.showLengkapiModal = true;
              this.searchKaryawanName = '';
              this.karyawanFound = false;
              this.isKaryawanBaru = false;
              this.formData.pengguna = '';
              
              // Reset items
              for(let key in this.formData.items) {
                  this.formData.items[key].no_aset = '';
                  this.formData.items[key].disabled = false;
              }
              
              // Prefill scanned SN into the correct category
              let upperSn = this.searchSn.toUpperCase();
              if(upperSn.startsWith('LAP') || upperSn.startsWith('MAC') || upperSn.startsWith('NUC')) {
                  this.formData.items.laptop.no_aset = upperSn;
              } else if(upperSn.startsWith('CHA')) {
                  this.formData.items.charger.no_aset = upperSn;
              } else if(upperSn.startsWith('MOU')) {
                  this.formData.items.mouse.no_aset = upperSn;
              } else if(upperSn.startsWith('LAN')) {
                  this.formData.items.lan_extender.no_aset = upperSn;
              } else if(upperSn.startsWith('HDS')) {
                  this.formData.items.headset.no_aset = upperSn;
              } else if(upperSn.startsWith('HP')) {
                  this.formData.items.hp_root.no_aset = upperSn;
              } else if(upperSn.startsWith('AUD')) {
                  this.formData.items.audio_jack.no_aset = upperSn;
              } else {
                  this.formData.items.laptop.no_aset = upperSn; // Default fallback
              }
          },
          async checkAsset() {
            this.searched = true;
            this.assetFound = false;
            if(this.searchSn === '') return;
            
            try {
                const response = await fetch(`/workspaceinventory/api/search/inventory?sn=${encodeURIComponent(this.searchSn)}`);
                const result = await response.json();
                
                if(result.found) {
                  this.assetFound = true;
                  this.assetData = result.data;
                }
            } catch (error) {
                console.error('Error fetching inventory:', error);
            }
          },
          async checkAssetInline(no_aset) {
            if (!no_aset) return;
            
            this.checkedStatus[no_aset] = { loading: true, found: false, data: null, conflict: false };
            
            try {
                const response = await fetch(`/workspaceinventory/api/search/inventory?sn=${encodeURIComponent(no_aset)}`);
                const result = await response.json();
                
                if(result.found) {
                  const txType = this.selectedTx?.type || '';
                  let isConflict = false;
                  
                  if (txType === 'peminjaman') {
                      isConflict = (result.data.status !== 'Disimpan' && result.data.status !== 'Aktif') || result.data.kondisi !== 'Baik';
                  } else {
                      isConflict = result.data.status !== 'Disimpan' || result.data.kondisi !== 'Baik';
                  }
                  
                  this.checkedStatus[no_aset] = { loading: false, found: true, data: result.data, conflict: isConflict };
                } else {
                  this.checkedStatus[no_aset] = { loading: false, found: false, data: null, conflict: false };
                }
                this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
            } catch (error) {
                this.checkedStatus[no_aset] = { loading: false, error: true, conflict: false };
            }
          },
          async deleteTxItem(id) {
              const resultSwal = await Swal.fire({
                  title: 'Hapus Barang?',
                  text: 'Apakah Anda yakin ingin menghapus barang ini dari transaksi?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#e11d48',
                  cancelButtonColor: '#64748b',
                  confirmButtonText: 'Ya, Hapus!',
                  cancelButtonText: 'Batal'
              });
              if(!resultSwal.isConfirmed) return;
              try {
                  const response = await fetch(`/workspaceinventory/validasi/item/${id}`, {
                      method: 'DELETE',
                      headers: {
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                          'Accept': 'application/json'
                      }
                  });
                  if(response.ok) {
                      this.selectedTx.items = this.selectedTx.items.filter(i => i.id !== id);
                      if (this.selectedTx.items.length === 0) {
                          this.showDetailModal = false;
                          window.location.reload();
                      }
                  } else {
                      alert('Gagal menghapus barang');
                  }
              } catch(e) {
                  console.error(e);
                  alert('Terjadi kesalahan sistem');
              }
          },
          get hasConflict() {
            if (!this.selectedTx?.items) return false;
            for (let i of this.selectedTx.items) {
                if (this.checkedStatus[i.no_aset]?.conflict) {
                    return true;
                }
            }
            return false;
          },
          init() {
            this.$watch('showLengkapiModal', () => { 
                this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
            });
            this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
            this.$watch('activeTab', () => { 
              this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
            });
            this.$watch('showDetailModal', () => { 
              this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
            });
          }
        }
      }
    
        
        document.addEventListener('DOMContentLoaded', () => {
            if(window.lucide) window.lucide.createIcons();
        });
    </script>
</body>
</html>
