<x-layout active="validasi" headerTitle="Validasi Data & Fisik">
    {{-- Flash Success --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-5 right-5 z-[100] flex items-center gap-2 bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg text-sm font-semibold">
        <i data-lucide="check-circle" class="w-4 h-4"></i>
        {{ session('success') }}
    </div>
    @endif

    <div x-data="validasiApp()" class="flex flex-col h-full space-y-6">
      
      <!-- Header & Tab Navigation -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
          <div>
              <h3 class="text-base font-bold text-slate-700">Pusat Validasi</h3>
              <p class="text-slate-500 text-xs">Pusat persetujuan transaksi dan validasi fisik barang.</p>
          </div>
          <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl self-stretch md:self-auto shrink-0 overflow-x-auto">
              <button @click="activeTab = 'inputan'" 
                      :class="activeTab === 'inputan' ? 'bg-white text-[#1d4ed8] shadow-sm font-bold' : 'text-slate-500 font-medium hover:bg-slate-50'" 
                      class="px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex items-center">
                  <i data-lucide="file-check-2" class="w-4 h-4 mr-2"></i>
                  Validasi Inputan User
              </button>
              <button @click="activeTab = 'fisik'" 
                      :class="activeTab === 'fisik' ? 'bg-white text-[#1d4ed8] shadow-sm font-bold' : 'text-slate-500 font-medium hover:bg-slate-50'" 
                      class="px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex items-center">
                  <i data-lucide="package-check" class="w-4 h-4 mr-2"></i>
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
      <div x-show="activeTab === 'fisik'" class="grid grid-cols-1 lg:grid-cols-3 gap-6" style="display: none;">
        <!-- Input Panel -->
        <div class="col-span-1 lg:col-span-1">
          <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100">
            <h4 class="text-sm font-bold text-slate-700 mb-4 flex items-center">
              <i data-lucide="keyboard" class="w-4 h-4 mr-2 text-[#1d4ed8]"></i>
              Input Barang
            </h4>
            
            <form @submit.prevent="checkAsset" class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Input No. Aset</label>
                <div class="relative">
                  <i data-lucide="keyboard" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                  <input type="text" x-model="searchSn" placeholder="Input No. Aset disini..." class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] font-mono outline-none transition-all">
                </div>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">Tekan Enter untuk memvalidasi</p>
              </div>
            </form>
          </div>
        </div>

        <!-- Result Panel -->
        <div class="col-span-1 lg:col-span-2">
          <!-- Empty State -->
          <div x-show="!assetFound && !searched" class="bg-slate-100 rounded-2xl border border-slate-200 border-dashed h-full min-h-[300px] flex flex-col items-center justify-center text-slate-400">
            <i data-lucide="box" class="w-16 h-16 mb-4 text-slate-300"></i>
            <p>Masukkan No. Aset untuk melihat detail barang.</p>
          </div>
          
          <!-- Not Found -->
          <div x-show="!assetFound && searched" class="bg-red-50 rounded-2xl border border-red-200 h-full min-h-[300px] flex flex-col items-center justify-center text-red-500" style="display: none;">
            <i data-lucide="x-circle" class="w-16 h-16 mb-4 text-red-400"></i>
            <h3 class="text-lg font-bold">Barang Tidak Ditemukan!</h3>
            <p class="text-sm mt-2">Pastikan No. Aset diketik dengan benar.</p>
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
            
            <div class="p-6 space-y-6 overflow-y-auto flex-1">
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
                                                    <div class="flex items-center space-x-2">
                                                        <template x-if="i.sn_lama !== null && i.sn_lama !== undefined && i.sn_lama !== ''">
                                                            <div class="flex items-center space-x-2">
                                                                <input type="text" x-model="i.sn_lama" class="font-mono text-sm font-bold text-rose-600 px-2 py-1 border border-rose-200 rounded-lg outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 w-32 bg-rose-50 transition-all hover:border-rose-300" title="Aset Lama">
                                                                <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400"></i>
                                                            </div>
                                                        </template>
                                                        <input type="text" x-model="i.no_aset" class="font-mono text-sm font-bold text-slate-800 px-2 py-1 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] w-48 bg-white transition-all hover:border-slate-300" placeholder="No Aset">
                                                    </div>
                                                    <div x-text="i.kategori + (i.keterangan ? ' - ' + i.keterangan : '')" class="text-[10px] text-slate-500 mt-1 uppercase tracking-wide font-bold"></div>
                                                </div>
                                                <button type="button" @click.prevent="checkAssetInline(i.no_aset)" class="px-3 py-1.5 bg-white border border-slate-200 text-[#1d4ed8] hover:bg-blue-50 rounded-lg text-[11px] font-bold flex items-center transition-colors shadow-sm">
                                                    <i data-lucide="search" class="w-3.5 h-3.5 mr-1.5"></i> Cek Data
                                                </button>
                                            </div>
                                            
                                            <!-- Result Area -->
                                            <div x-show="checkedStatus[i.no_aset]" class="p-3 border-t border-slate-100 text-xs" style="display: none;" x-transition>
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
                                                    <p class="text-slate-500 text-[10px] ml-5 leading-relaxed font-medium">Aset ini tidak dapat ditransaksikan karena statusnya bukan <b>'Disimpan'</b> atau kondisinya tidak <b>'Baik'</b>.</p>
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
        <div @click.away="openConfirmModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-fade-in text-center p-6">
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
              this.$nextTick(() => { if(window.lucide) window.lucide.createIcons(); });
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
              this.$nextTick(() => { if(window.lucide) window.lucide.createIcons(); });
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
                  const isConflict = result.data.status !== 'Disimpan' || result.data.kondisi !== 'Baik';
                  this.checkedStatus[no_aset] = { loading: false, found: true, data: result.data, conflict: isConflict };
                } else {
                  this.checkedStatus[no_aset] = { loading: false, found: false, data: null, conflict: false };
                }
                this.$nextTick(() => { if(window.lucide) window.lucide.createIcons(); });
            } catch (error) {
                this.checkedStatus[no_aset] = { loading: false, error: true, conflict: false };
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


