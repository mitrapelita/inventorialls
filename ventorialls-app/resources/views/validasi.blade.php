<x-layout active="validasi" headerTitle="Validasi Data & Fisik">
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
                          <th class="px-5 py-3 font-semibold">Item Data</th>
                          <th class="px-5 py-3 font-semibold">Status</th>
                          <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                      <template x-for="item in txList" :key="item.id">
                          <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                              <td class="px-5 py-3 font-semibold text-indigo-600" x-text="item.tipe"></td>
                              <td class="px-5 py-3 font-medium text-slate-700" x-text="item.pengaju"></td>
                              <td class="px-5 py-3 text-xs text-slate-500">
                                  <template x-for="(i, idx) in item.itemList" :key="idx">
                                      <div x-text="i.nama" class="mb-0.5"></div>
                                  </template>
                              </td>
                              <td class="px-5 py-3">
                                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border" 
                                        :class="{
                                            'bg-amber-50 text-amber-600 border-amber-200': item.status === 'Menunggu Validasi',
                                            'bg-emerald-50 text-emerald-600 border-emerald-200': item.status === 'Disetujui',
                                            'bg-rose-50 text-rose-600 border-rose-200': item.status === 'Ditolak'
                                        }" x-text="item.status"></span>
                              </td>
                              <td class="px-5 py-3 text-center">
                                  <button @click="selectedTx = item; showDetailModal = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Detail">
                                      <i data-lucide="eye" class="w-4 h-4"></i>
                                  </button>
                              </td>
                          </tr>
                      </template>
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
        <div @click.away="showDetailModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="font-bold text-slate-800">Detail Validasi Transaksi</h3>
                    <p class="text-xs text-slate-500" x-text="selectedTx ? selectedTx.tipe : ''"></p>
                </div>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            
            <div class="p-6 space-y-6 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <template x-if="selectedTx">
                    <div class="space-y-6">
                        <!-- Info Section -->
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-1">Pengaju</span>
                                <span class="text-sm font-bold text-slate-800" x-text="selectedTx.pengaju"></span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-1">Status</span>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold border" 
                                      :class="{
                                          'bg-amber-50 text-amber-600 border-amber-200': selectedTx.status === 'Menunggu Validasi',
                                          'bg-emerald-50 text-emerald-600 border-emerald-200': selectedTx.status === 'Disetujui',
                                          'bg-rose-50 text-rose-600 border-rose-200': selectedTx.status === 'Ditolak'
                                      }" x-text="selectedTx.status"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-1">Item Data</span>
                                <div class="text-sm font-medium text-slate-700">
                                    <template x-for="(i, idx) in selectedTx.itemList" :key="idx">
                                        <div class="mb-2">
                                            <template x-if="selectedTx.status === 'Menunggu Validasi'">
                                                <input type="text" x-model="i.nama" class="w-full px-3 py-1.5 text-sm font-medium border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] bg-white transition-colors" title="Edit Data jika ada kesalahan ketik">
                                            </template>
                                            <template x-if="selectedTx.status !== 'Menunggu Validasi'">
                                                <div x-text="i.nama" class="text-sm font-medium text-slate-700 py-1.5"></div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Bukti Section -->
                        <div class="space-y-4">
                            <template x-for="(i, idx) in selectedTx.itemList" :key="idx">
                                <div>
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2" x-text="'Foto Bukti - ' + i.nama"></span>
                                    <div class="bg-slate-100 rounded-xl border border-slate-200 overflow-hidden flex flex-col items-center justify-center p-4 min-h-[160px] text-slate-400">
                                        <i data-lucide="image" class="w-12 h-12 text-slate-300 mb-2"></i>
                                        <span class="text-xs text-slate-500 font-semibold" x-text="i.foto"></span>
                                        <span class="text-[10px] text-slate-400 mt-1">Uploaded by user via Mobile Camera</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end space-x-3">
                <button @click="showDetailModal = false" class="px-4 py-2 text-slate-500 hover:bg-slate-200 hover:text-slate-700 rounded-xl font-medium transition-colors text-sm">
                    Tutup
                </button>
                <template x-if="selectedTx && selectedTx.status === 'Menunggu Validasi'">
                    <div class="flex space-x-2">
                        <button @click="rejectTx(selectedTx.id)" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-4 py-2 rounded-xl text-sm font-bold border border-rose-200 transition-colors">
                            Tolak
                        </button>
                        <button @click="approveTx(selectedTx.id)" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-soft transition-colors">
                            Setujui
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
    
    </div> <!-- END of validasiApp -->

    @push('scripts')
    <script>
      function validasiApp() {
        return {
          activeTab: 'inputan',
          showDetailModal: false,
          selectedTx: null,
          txList: [
            { 
              id: 1, 
              tipe: 'Serah Terima', 
              pengaju: 'Fikri Nur', 
              status: 'Menunggu Validasi',
              itemList: [
                { nama: 'Laptop: UP-LAP-010', foto: 'Foto Bukti No. Aset - Laptop' },
                { nama: 'Mouse: UP-MOU-011', foto: 'Foto Bukti No. Aset - Mouse' }
              ]
            },
            { 
              id: 2, 
              tipe: 'Peminjaman Eksternal', 
              pengaju: 'Jhon Doe', 
              status: 'Menunggu Validasi',
              itemList: [
                { nama: 'Laptop: UP-LAP-005', foto: 'Foto Bukti No. Aset - Laptop' }
              ]
            },
          ],
          searchSn: '',
          searched: false,
          assetFound: false,
          assetData: {},
          db: [
            { jenis: 'Laptop', merk: 'Lenovo Thinkpad T14', sn: 'UP-LAP-001', pengguna: 'Gita Novia Ashari', department: 'Agent', kondisi: 'Baik', status: 'Aktif' },
            { jenis: 'Laptop', merk: 'Lenovo Thinkpad T14', sn: 'UP-LAP-002', pengguna: 'Mahfuddin', department: 'IT', kondisi: 'Baik', status: 'Aktif' },
          ],
          checkAsset() {
            if(!this.searchSn) return;
            this.searched = true;
            this.assetFound = false;
            
            const found = this.db.find(item => item.sn.toUpperCase() === this.searchSn.toUpperCase());
            
            if(found) {
              this.assetFound = true;
              this.assetData = found;
            }
          },
          approveTx(id) {
            const tx = this.txList.find(t => t.id === id);
            if(tx) tx.status = 'Disetujui';
            this.showDetailModal = false;
            // update lucide icons inside details
            this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
          },
          rejectTx(id) {
            const tx = this.txList.find(t => t.id === id);
            if(tx) tx.status = 'Ditolak';
            this.showDetailModal = false;
            this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
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


