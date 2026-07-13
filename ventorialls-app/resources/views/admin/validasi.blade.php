<x-layout active="validasi" headerTitle="Validasi Data & Fisik">

    <div x-data="validasiApp()" class="flex flex-col h-full space-y-6">
      
      <!-- Header & Tab Navigation -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
          <div>
              <h3 class="text-base font-bold text-slate-700">Pusat Validasi</h3>
              <p class="text-slate-500 text-xs">Pusat persetujuan transaksi dan validasi fisik barang.</p>
          </div>
          <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl self-stretch md:self-auto shrink-0 overflow-x-auto scrollbar-hide">
              <button @click="activeTab = 'inputan'" 
                      :class="activeTab === 'inputan' ? 'bg-white text-[#1d4ed8] shadow-sm font-bold' : 'text-slate-500 font-medium hover:bg-slate-50'" 
                      class="px-3 py-2 md:px-4 md:py-2 rounded-lg text-xs md:text-sm transition-all whitespace-nowrap flex items-center">
                  <i data-lucide="file-check-2" class="w-4 h-4 mr-1.5 md:mr-2"></i>
                  Validasi Inputan User
              </button>
              <button @click="activeTab = 'fisik'" 
                      :class="activeTab === 'fisik' ? 'bg-white text-[#1d4ed8] shadow-sm font-bold' : 'text-slate-500 font-medium hover:bg-slate-50'" 
                      class="px-3 py-2 md:px-4 md:py-2 rounded-lg text-xs md:text-sm transition-all whitespace-nowrap flex items-center">
                  <i data-lucide="package-check" class="w-4 h-4 mr-1.5 md:mr-2"></i>
                  Validasi Fisik & Tools
              </button>
          </div>
      </div>

      <!-- Tab 1: Validasi Inputan User -->
      <div x-show="activeTab === 'inputan'" class="flex-1 flex flex-col bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden" style="display: none;">
          <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <h4 class="font-bold text-slate-700 text-sm">Daftar Transaksi Menunggu Validasi</h4>
          </div>
          <div class="flex-1 overflow-auto">
              <table class="w-full text-left border-collapse whitespace-nowrap">
                  <thead>
                      <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                          <th class="px-5 py-3 font-semibold">Tipe Transaksi</th>
                          <th class="px-5 py-3 font-semibold">Pengaju</th>
                          <th class="px-5 py-3 font-semibold text-center">Item Data</th>
                          <th class="px-5 py-3 font-semibold">Status</th>
                          <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                      @forelse($transactions as $item)
                          <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                              <td class="px-5 py-3 font-semibold text-indigo-600 uppercase">{{ str_replace('_', ' ', $item->type) }}</td>
                              <td class="px-5 py-3 font-medium text-slate-700">{{ $item->nama_pengaju }}</td>
                              <td class="px-5 py-3 text-center" 
                                  x-data="{ showTooltip: false, mouseX: 0, mouseY: 0 }" 
                                  @mouseenter="showTooltip = true" 
                                  @mouseleave="showTooltip = false" 
                                  @mousemove="mouseX = $event.clientX; mouseY = $event.clientY">
                                  <button @click='openModal(@json($item))' class="flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold w-16 mx-auto hover:bg-blue-50 hover:text-[#1d4ed8] hover:border-blue-200 transition-colors shadow-sm cursor-pointer group">
                                      <i data-lucide="box" class="w-3.5 h-3.5 mr-1.5 text-slate-400 group-hover:text-[#1d4ed8]"></i>
                                      <span>{{ $item->items->count() }}</span>
                                  </button>
                                  
                                  <!-- Hover Tooltip (Teleported to body to avoid overflow clipping) -->
                                  <template x-teleport="body">
                                      <div x-show="showTooltip" 
                                           x-cloak 
                                           :style="`left: ${mouseX + 15}px; top: ${mouseY + 15}px;`" 
                                           class="fixed z-[100] bg-white rounded-xl shadow-md border border-slate-100 p-3 min-w-[150px] pointer-events-none transition-opacity duration-150">
                                          <div class="font-bold border-b border-slate-100 pb-2 mb-2 text-slate-800 text-xs">Daftar Aset ({{ $item->items->count() }})</div>
                                          <div class="flex flex-col gap-1.5">
                                              @foreach($item->items as $detail)
                                                  <div class="flex items-center">
                                                      <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500 mr-2 flex-shrink-0"></i>
                                                      <span class="font-mono text-xs text-slate-700 font-semibold">{{ $detail->no_aset }}</span>
                                                  </div>
                                              @endforeach
                                          </div>
                                      </div>
                                  </template>
                              </td>
                              <td class="px-5 py-3">
                                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border bg-amber-50 text-amber-600 border-amber-200">
                                      Menunggu Validasi
                                  </span>
                              </td>
                              <td class="px-5 py-3 text-center">
                                  <button @click='openModal(@json($item))' class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Validasi Transaksi">
                                      <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                                  </button>
                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                  <i data-lucide="check-circle-2" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                  Tidak ada transaksi yang menunggu validasi.
                              </td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>

      <!-- Tab 2: Validasi Fisik & Tools -->
      <div x-show="activeTab === 'fisik'" class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 min-w-0" style="display: none;">
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
                <form action="{{ route('transaksi.serah-terima') }}" method="POST" enctype="multipart/form-data" class="space-y-4 md:space-y-6">
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

    <!-- DETAIL VALIDASI MODAL -->
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;"
         x-transition>
        <div @click.away="showDetailModal = false" class="bg-white w-full max-w-md flex flex-col rounded-2xl shadow-2xl overflow-hidden animate-fade-in max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                <div>
                    <h3 class="font-bold text-slate-800">Detail Validasi Transaksi</h3>
                    <p class="text-xs text-slate-500 uppercase" x-text="selectedTx ? selectedTx.type.replace('_', ' ') : ''"></p>
                </div>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            
            <div class="p-4 md:p-6 space-y-4 md:space-y-6 overflow-y-auto flex-1">
                <template x-if="selectedTx">
                    <div class="space-y-6">
                        <!-- Info Section -->
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-1">Pengaju</span>
                                <span class="text-sm font-bold text-slate-800" x-text="selectedTx.nama_pengaju"></span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-1">Status</span>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold border bg-amber-50 text-amber-600 border-amber-200">
                                    Menunggu Validasi
                                </span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-1">Item Data</span>
                                <div class="text-sm font-medium text-slate-700">
                                    <template x-for="(i, idx) in selectedTx.items" :key="idx">
                                        <div class="mb-3 border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                                            <div class="p-3 flex items-center justify-between bg-slate-50">
                                                <div>
                                                    <!-- For Normal/Peminjaman -->
                                                    <template x-if="!i.sn_lama">
                                                        <div class="flex items-center space-x-2">
                                                            <input type="text" x-model="i.no_aset" class="font-mono text-sm font-bold text-slate-800 px-2 py-1 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] w-48 bg-white transition-all hover:border-slate-300" placeholder="No Aset">
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- For Penukaran -->
                                                    <template x-if="i.sn_lama !== null && i.sn_lama !== undefined && i.sn_lama !== ''">
                                                        <div class="flex flex-col space-y-2">
                                                            <div class="flex items-center space-x-2">
                                                                <span class="text-[10px] font-bold text-rose-500 w-10">LAMA:</span>
                                                                <input type="text" x-model="i.sn_lama" class="font-mono text-sm font-bold text-rose-600 px-2 py-1 border border-rose-200 rounded-lg outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 w-32 bg-rose-50" title="Aset Lama">
                                                                <button type="button" @click.prevent="checkAssetInline(i.sn_lama)" class="px-2 py-1 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-[10px] font-bold shadow-sm whitespace-nowrap">Cek</button>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="text-[10px] font-bold text-blue-600 w-10">BARU:</span>
                                                                <input type="text" x-model="i.no_aset" class="font-mono text-sm font-bold text-slate-800 px-2 py-1 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] w-32 bg-white" placeholder="No Aset Baru">
                                                                <button type="button" @click.prevent="checkAssetInline(i.no_aset)" class="px-2 py-1 bg-white border border-slate-200 text-[#1d4ed8] hover:bg-blue-50 rounded-lg text-[10px] font-bold shadow-sm whitespace-nowrap">Cek</button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-text="(i.jenis_barang || i.kategori || '') + (i.keterangan ? ' - ' + i.keterangan : '')" class="text-[10px] text-slate-500 mt-1 uppercase tracking-wide font-bold"></div>
                                                </div>
                                                <div class="flex flex-col space-y-1.5 shrink-0 ml-3">
                                                    <button type="button" x-show="!i.sn_lama" @click.prevent="checkAssetInline(i.no_aset)" class="px-3 py-1.5 bg-white border border-slate-200 text-[#1d4ed8] hover:bg-blue-50 rounded-lg text-[11px] font-bold flex items-center justify-center transition-colors shadow-sm whitespace-nowrap w-full">
                                                        <i data-lucide="search" class="w-3.5 h-3.5 mr-1.5"></i> Cek Data
                                                    </button>
                                                    <button type="button" @click.prevent="deleteTxItem(i.id)" class="px-3 py-1.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-[11px] font-bold flex items-center justify-center transition-colors shadow-sm whitespace-nowrap w-full">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i> Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Result Area for sn_lama (Aset Lama) -->
                                            <template x-if="i.sn_lama !== null && i.sn_lama !== undefined && i.sn_lama !== ''">
                                                <div x-show="checkedStatus[i.sn_lama]" class="p-3 border-t border-rose-100 text-xs bg-rose-50/30" style="display: none;" x-transition>
                                                    <div class="font-bold text-rose-700 mb-2 uppercase tracking-wider text-[10px]">Info Aset Lama:</div>
                                                    
                                                    <!-- Loading -->
                                                    <div x-show="checkedStatus[i.sn_lama]?.loading" class="flex items-center text-rose-400 font-medium">
                                                        <i data-lucide="loader-2" class="w-4 h-4 mr-2 animate-spin"></i> Memeriksa database...
                                                    </div>
                                                    
                                                    <!-- Found -->
                                                    <div x-show="checkedStatus[i.sn_lama]?.found && !checkedStatus[i.sn_lama]?.loading">
                                                        <div class="grid grid-cols-2 gap-2 text-[10px] bg-white p-2.5 rounded-lg border border-rose-100 shadow-sm">
                                                            <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Pengguna</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.sn_lama]?.data?.pengguna || '-'"></span></div>
                                                            <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Status</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.sn_lama]?.data?.status || '-'"></span></div>
                                                            <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Kondisi</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.sn_lama]?.data?.kondisi || '-'"></span></div>
                                                            <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Jenis</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.sn_lama]?.data?.jenis || '-'"></span></div>
                                                        </div>
                                                    </div>

                                                    <!-- Not Found -->
                                                    <div x-show="checkedStatus[i.sn_lama] && !checkedStatus[i.sn_lama]?.found && !checkedStatus[i.sn_lama]?.loading && !checkedStatus[i.sn_lama]?.error">
                                                        <div class="flex items-center text-rose-500 font-bold mb-1.5">
                                                            <i data-lucide="x-circle" class="w-4 h-4 mr-1.5"></i> Aset Lama Tidak Ditemukan
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Result Area for no_aset -->
                                            <div x-show="checkedStatus[i.no_aset]" class="p-3 border-t border-slate-100 text-xs" style="display: none;" x-transition>
                                                <template x-if="i.sn_lama !== null && i.sn_lama !== undefined && i.sn_lama !== ''">
                                                    <div class="font-bold text-blue-700 mb-2 uppercase tracking-wider text-[10px]">Info Aset Baru:</div>
                                                </template>
                                                <!-- Loading -->
                                                <div x-show="checkedStatus[i.no_aset]?.loading" class="flex items-center text-slate-400 font-medium">
                                                    <i data-lucide="loader-2" class="w-4 h-4 mr-2 animate-spin"></i> Memeriksa database...
                                                </div>
                                                
                                                <!-- Found -->
                                                <!-- Found without Conflict -->
                                                <div x-show="checkedStatus[i.no_aset]?.found && !checkedStatus[i.no_aset]?.loading && !checkedStatus[i.no_aset]?.conflict">
                                                    <div class="flex items-center text-emerald-600 font-bold mb-2">
                                                        <i data-lucide="check-circle-2" class="w-4 h-4 mr-1.5"></i> Terdaftar & Siap Digunakan
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2 text-[10px] bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                                        <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Pengguna</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.no_aset]?.data?.pengguna || '-'"></span></div>
                                                        <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Status</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.no_aset]?.data?.status || '-'"></span></div>
                                                        <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Kondisi</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.no_aset]?.data?.kondisi || '-'"></span></div>
                                                        <div><span class="text-slate-400 block mb-0.5 uppercase tracking-wider font-bold">Jenis</span> <span class="font-bold text-slate-700" x-text="checkedStatus[i.no_aset]?.data?.jenis || '-'"></span></div>
                                                    </div>
                                                </div>

                                                <!-- Conflict -->
                                                <div x-show="checkedStatus[i.no_aset]?.conflict && !checkedStatus[i.no_aset]?.loading">
                                                    <div class="flex items-center text-rose-500 font-bold mb-1.5">
                                                        <i data-lucide="alert-triangle" class="w-4 h-4 mr-1.5"></i> Aset Tidak Memenuhi Syarat
                                                    </div>
                                                    <p class="text-slate-500 text-[10px] ml-5 leading-relaxed font-medium">
                                                        Aset ini tidak dapat ditransaksikan karena kondisinya tidak <b>'Baik'</b>, atau statusnya bukan 
                                                        <template x-if="selectedTx?.type === 'peminjaman'"><b>'Disimpan' / 'Aktif'</b></template>
                                                        <template x-if="selectedTx?.type !== 'peminjaman'"><b>'Disimpan'</b></template>.
                                                    </p>
                                                    <div class="grid grid-cols-2 gap-2 text-[10px] bg-rose-50 p-2.5 mt-2 rounded-lg border border-rose-100">
                                                        <div><span class="text-rose-400 block mb-0.5 uppercase tracking-wider font-bold">Status Saat Ini</span> <span class="font-bold text-rose-700" x-text="checkedStatus[i.no_aset]?.data?.status || '-'"></span></div>
                                                        <div><span class="text-rose-400 block mb-0.5 uppercase tracking-wider font-bold">Kondisi</span> <span class="font-bold text-rose-700" x-text="checkedStatus[i.no_aset]?.data?.kondisi || '-'"></span></div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Not Found -->
                                                <div x-show="!checkedStatus[i.no_aset]?.found && !checkedStatus[i.no_aset]?.loading && !checkedStatus[i.no_aset]?.error">
                                                    <div class="flex items-center text-rose-500 font-bold mb-1.5">
                                                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1.5"></i> Belum Terdaftar
                                                    </div>
                                                    <p class="text-slate-500 text-[10px] ml-5 leading-relaxed font-medium">Aset belum ada di master data. Menyetujui transaksi ini akan otomatis mendaftarkannya.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Bukti Section -->
                        <div class="space-y-4">
                            <template x-for="(i, idx) in selectedTx.items" :key="idx">
                                <div>
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2" x-text="'Foto Bukti - ' + i.no_aset"></span>
                                    <div class="bg-slate-100 rounded-xl border border-slate-200 overflow-hidden flex flex-col items-center justify-center min-h-[160px]">
                                        <template x-if="i.foto_path">
                                            <img :src="'/storage/' + i.foto_path" class="w-full h-auto object-cover max-h-64">
                                        </template>
                                        <template x-if="!i.foto_path">
                                            <div class="text-center p-4">
                                                <i data-lucide="image" class="w-12 h-12 text-slate-300 mx-auto mb-2"></i>
                                                <span class="text-xs text-slate-500 font-semibold">Tidak ada foto</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex flex-col space-y-3">
                <template x-if="selectedTx">
                    <div class="flex flex-col w-full gap-3">
                        <form :action="'/workspaceinventory/validasi/' + selectedTx.id + '/approve'" method="POST" class="w-full" @submit.prevent="triggerConfirm('approve', $event.target)">
                            @csrf
                            <template x-for="item in selectedTx.items" :key="item.id">
                                <div>
                                    <input type="hidden" :name="'items[' + item.id + '][no_aset]'" :value="item.no_aset">
                                    <template x-if="item.sn_lama !== null && item.sn_lama !== undefined && item.sn_lama !== ''">
                                        <input type="hidden" :name="'items[' + item.id + '][sn_lama]'" :value="item.sn_lama">
                                    </template>
                                </div>
                            </template>
                            <button type="submit" 
                                                    :disabled="hasConflict"
                                                    :class="{'opacity-50 cursor-not-allowed': hasConflict}"
                                                    class="w-full bg-emerald-500 hover:bg-emerald-600 transition-colors text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm flex items-center justify-center">
                                <i data-lucide="check" class="w-4 h-4 mr-2"></i> <span x-text="hasConflict ? 'Ada Aset yang Konflik' : 'Setujui Transaksi'"></span>
                            </button>
                        </form>
                        
                        <form :action="'/workspaceinventory/validasi/' + selectedTx.id + '/reject'" method="POST" class="w-full" @submit.prevent="triggerConfirm('reject', $event.target)">
                            @csrf
                            <input type="text" name="catatan_admin" required placeholder="Alasan penolakan..." class="w-full px-4 py-2 mb-3 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 focus:bg-white transition-colors">
                            <button type="submit" class="w-full bg-white border border-rose-200 hover:border-rose-500 hover:text-rose-600 transition-colors text-rose-500 px-6 py-2.5 rounded-xl font-medium shadow-sm text-sm flex items-center justify-center">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i> Tolak
                            </button>
                        </form>
                    </div>
                </template>
                <button @click="showDetailModal = false" class="w-full px-4 py-2 text-slate-500 hover:bg-slate-200 hover:text-slate-700 rounded-xl font-medium transition-colors text-sm text-center">
                    Tutup Modal
                </button>
            </div>
        </div>
    </div>
    

    <!-- CONFIRMATION MODAL -->
    <div x-show="openConfirmModal" 
         class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openConfirmModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-fade-in text-center p-4 md:p-6">
            <template x-if="confirmType === 'approve'">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500"></i>
                </div>
            </template>
            <template x-if="confirmType === 'reject'">
                <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-8 h-8 text-rose-500"></i>
                </div>
            </template>
            <h3 class="text-lg font-bold text-slate-800 mb-2" x-text="confirmType === 'approve' ? 'Setujui Transaksi?' : 'Tolak Transaksi?'"></h3>
            <p class="text-sm text-slate-500 mb-6" x-text="confirmType === 'approve' ? 'Yakin ingin menyetujui dan memproses transaksi ini ke Master Data?' : 'Yakin ingin menolak transaksi ini?'"></p>
            
            <div class="flex space-x-3">
                <button type="button" @click="openConfirmModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="button" @click="submitPending()" :class="confirmType === 'approve' ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-rose-500 hover:bg-rose-600'" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white transition-colors shadow-soft" x-text="confirmType === 'approve' ? 'Ya, Setujui' : 'Ya, Tolak'"></button>
            </div>
        </div>
    </div>

    </div>
    
    @push('scripts')
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
    </script>
    @endpush
</x-layout>
