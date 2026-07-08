<x-layout active="mutasi" headerTitle="Log Inventaris">
    <div x-data="mutasiApp()" class="flex flex-col h-full space-y-6">
      
      <!-- Top Action & Tabs Bar -->
      <div class="flex justify-between items-center w-full">
          <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl overflow-x-auto">
              <button @click="activeTab = 'masuk'" 
                      :class="activeTab === 'masuk' ? 'bg-white text-[#1d4ed8] shadow-sm font-bold' : 'text-slate-500 font-medium hover:bg-slate-50'" 
                      class="px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex items-center">
                  <i data-lucide="arrow-down-circle" class="w-4 h-4 mr-2"></i>
                  Barang Masuk
              </button>
              <button @click="activeTab = 'keluar'" 
                      :class="activeTab === 'keluar' ? 'bg-white text-[#1d4ed8] shadow-sm font-bold' : 'text-slate-500 font-medium hover:bg-slate-50'" 
                      class="px-4 py-2 rounded-lg text-sm transition-all whitespace-nowrap flex items-center">
                  <i data-lucide="arrow-up-circle" class="w-4 h-4 mr-2"></i>
                  Barang Keluar
              </button>
          </div>
          
          <button @click="openModal = true" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-4 py-2 rounded-xl text-sm font-medium shadow-soft shadow-[#1d4ed8]/30 flex items-center transition-all shrink-0">
              <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
              Tambah Data
          </button>
      </div>

      <!-- Main Container -->
      <div class="bg-white rounded-2xl shadow-soft border border-slate-100 flex flex-col flex-1 overflow-hidden">
          
          <!-- Search Bar & Filters -->
          <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 bg-white">
              <div class="relative w-full sm:w-80">
                  <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                  <input type="text" placeholder="Cari Jenis Barang, Merk..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm">
              </div>
              <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                  <select class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 outline-none text-xs font-medium cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">
                      <option value="semua">Semua Periode</option>
                      <option value="hari">Hari Ini</option>
                      <option value="minggu">Minggu Ini</option>
                      <option value="bulan">Bulan Ini</option>
                      <option value="tahun">Tahun Ini</option>
                  </select>
                  <button class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 flex items-center text-xs font-medium transition-colors shadow-sm">
                      <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5 text-slate-400"></i>
                      Export
                  </button>
              </div>
          </div>

      <!-- Tab 1: Barang Masuk -->
      <div x-show="activeTab === 'masuk'" class="flex-1 flex flex-col bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden" style="display: none;">
          <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <h4 class="font-bold text-slate-700 text-sm">Daftar Barang Masuk</h4>
          </div>
          <div class="flex-1 overflow-auto">
              <table class="w-full text-left border-collapse whitespace-nowrap">
                  <thead>
                      <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                          <th class="px-5 py-3 font-semibold text-center w-16">No</th>
                          <th class="px-5 py-3 font-semibold">Jenis Barang</th>
                          <th class="px-5 py-3 font-semibold">Merk / Tipe</th>
                          <th class="px-5 py-3 font-semibold text-right">Jumlah</th>
                          <th class="px-5 py-3 font-semibold">Satuan</th>
                          <th class="px-5 py-3 font-semibold">Tanggal Masuk</th>
                          <th class="px-5 py-3 font-semibold">Keterangan</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                      <template x-for="(item, index) in barangMasuk" :key="index">
                          <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                              <td class="px-5 py-3 text-center text-slate-500 font-medium" x-text="index + 1"></td>
                              <td class="px-5 py-3 font-semibold text-slate-700" x-text="item.jenis"></td>
                              <td class="px-5 py-3 font-medium text-slate-600" x-text="item.merk"></td>
                              <td class="px-5 py-3 text-right font-mono font-semibold text-[#1d4ed8]" x-text="item.jumlah"></td>
                              <td class="px-5 py-3 text-xs font-medium text-slate-500" x-text="item.satuan"></td>
                              <td class="px-5 py-3 text-slate-600" x-text="item.tanggal"></td>
                              <td class="px-5 py-3 text-slate-500 italic" x-text="item.keterangan || '-'"></td>
                          </tr>
                      </template>
                  </tbody>
              </table>
          </div>
      </div>

      <!-- Tab 2: Barang Keluar -->
      <div x-show="activeTab === 'keluar'" class="flex-1 flex flex-col bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden" style="display: none;">
          <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <h4 class="font-bold text-slate-700 text-sm">Daftar Barang Keluar</h4>
          </div>
          <div class="flex-1 overflow-auto">
              <table class="w-full text-left border-collapse whitespace-nowrap">
                  <thead>
                      <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                          <th class="px-5 py-3 font-semibold text-center w-16">No</th>
                          <th class="px-5 py-3 font-semibold">Jenis Barang</th>
                          <th class="px-5 py-3 font-semibold">Merk / Tipe</th>
                          <th class="px-5 py-3 font-semibold text-right">Jumlah</th>
                          <th class="px-5 py-3 font-semibold">Satuan</th>
                          <th class="px-5 py-3 font-semibold">Tanggal Keluar</th>
                          <th class="px-5 py-3 font-semibold">Keterangan</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                      <template x-for="(item, index) in barangKeluar" :key="index">
                          <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                              <td class="px-5 py-3 text-center text-slate-500 font-medium" x-text="index + 1"></td>
                              <td class="px-5 py-3 font-semibold text-slate-700" x-text="item.jenis"></td>
                              <td class="px-5 py-3 font-medium text-slate-600" x-text="item.merk || '-'"></td>
                              <td class="px-5 py-3 text-right font-mono font-semibold text-rose-600" x-text="item.jumlah"></td>
                              <td class="px-5 py-3 text-xs font-medium text-slate-500" x-text="item.satuan || '-'"></td>
                              <td class="px-5 py-3 text-slate-600" x-text="item.tanggal"></td>
                              <td class="px-5 py-3 text-slate-500 italic" x-text="item.keterangan || '-'"></td>
                          </tr>
                      </template>
                  </tbody>
              </table>
          </div>
      </div>
      
      </div>

    <!-- MODAL TAMBAH DATA -->
    <div x-data="{ openModal: false }" @open-add-modal.window="openModal = true">
        <div x-show="openModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
             style="display: none;">
            <div @click.away="openModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Form Pencatatan</h2>
                        <p class="text-xs text-slate-500">Isi detail barang yang masuk atau keluar.</p>
                    </div>
                    <button @click="openModal = false" class="text-slate-400 hover:text-rose-500 transition-colors p-2 hover:bg-rose-50 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe Mutasi</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="tipe" value="masuk" checked class="text-emerald-500 focus:ring-emerald-500">
                                    <span class="text-sm font-medium">Barang Masuk</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="tipe" value="keluar" class="text-rose-500 focus:ring-rose-500">
                                    <span class="text-sm font-medium">Barang Keluar</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Barang</label>
                            <input type="text" placeholder="Cth: Mouse, Laptop..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Merk / Tipe</label>
                            <input type="text" placeholder="Cth: Logitech B100..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah</label>
                                <input type="number" placeholder="0" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Satuan</label>
                                <input type="text" placeholder="Cth: Unit, Pcs..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal</label>
                            <input type="date" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan (Opsional)</label>
                            <textarea rows="2" placeholder="Catatan tambahan..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]"></textarea>
                        </div>
                    </form>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end space-x-3">
                    <button @click="openModal = false" class="px-5 py-2.5 text-slate-500 hover:bg-slate-200 hover:text-slate-700 rounded-xl font-medium transition-colors text-sm">
                        Batal
                    </button>
                    <button @click="openModal = false" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-5 py-2.5 rounded-xl font-medium shadow-soft text-sm">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
      function mutasiApp() {
        return {
          activeTab: 'masuk',
          barangMasuk: [],
          barangKeluar: []
        }
      }
    </script>
    @endpush
</x-layout>
