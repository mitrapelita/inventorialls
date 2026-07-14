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
          
          <button @click="formType = activeTab; $dispatch('open-add-modal')" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-4 py-2 rounded-xl text-sm font-medium shadow-soft shadow-[#1d4ed8]/30 flex items-center transition-all shrink-0">
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
              <div class="flex items-center space-x-3 bg-white px-3 py-1.5 rounded-lg shadow-sm border transition-all" :class="selectedMasuk.length > 0 ? 'border-rose-200' : 'border-slate-200 opacity-60'">
                  <span x-show="selectedMasuk.length > 0" class="text-xs font-semibold text-rose-600">
                      <span x-text="selectedMasuk.length"></span> Terpilih
                  </span>
                  <button type="button" @click="bulkDeleteMasuk" class="px-3 py-1 rounded text-xs font-semibold shadow-sm transition-all flex items-center" :class="selectedMasuk.length > 0 ? 'bg-rose-500 hover:bg-rose-600 text-white cursor-pointer' : 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200'" :disabled="selectedMasuk.length === 0">
                      <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i> Hapus
                  </button>
              </div>
          </div>
          <div class="flex-1 overflow-auto">
              <table class="w-full text-left border-collapse whitespace-nowrap">
                  <thead>
                      <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                          <th class="px-4 py-3 text-center w-12">
                              <input type="checkbox" :checked="isAllMasukSelected" @change="toggleAllMasuk($event.target.checked)" class="text-[#1d4ed8] focus:ring-[#1d4ed8] rounded border-slate-300">
                          </th>
                          <th class="px-5 py-3 font-semibold text-center w-16">No</th>
                          <th class="px-5 py-3 font-semibold">Jenis Barang</th>
                          <th class="px-5 py-3 font-semibold">Merk / Tipe</th>
                          <th class="px-5 py-3 font-semibold text-right">Jumlah</th>
                          <th class="px-5 py-3 font-semibold">Satuan</th>
                          <th class="px-5 py-3 font-semibold">Tanggal Masuk</th>
                          <th class="px-5 py-3 font-semibold">Keterangan</th>
                          <th class="px-5 py-3 font-semibold text-center w-24">Aksi</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                      <template x-for="(item, index) in barangMasuk" :key="index">
                          <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                              <td class="px-4 py-3 text-center">
                                  <input type="checkbox" :value="item.id" x-model="selectedMasuk" class="text-[#1d4ed8] focus:ring-[#1d4ed8] rounded border-slate-300">
                              </td>
                              <td class="px-5 py-3 text-center text-slate-500 font-medium" x-text="index + 1"></td>
                              <td class="px-5 py-3 font-semibold text-slate-700" x-text="String(item.jenis || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()).replace('Lan ', 'LAN ').replace('Hp ', 'HP ')"></td>
                              <td class="px-5 py-3 font-medium text-slate-600" x-text="item.merk"></td>
                              <td class="px-5 py-3 text-right font-mono font-semibold text-[#1d4ed8]" x-text="item.jumlah"></td>
                              <td class="px-5 py-3 text-xs font-medium text-slate-500" x-text="item.satuan"></td>
                              <td class="px-5 py-3 text-slate-600" x-text="item.tanggal"></td>
                              <td class="px-5 py-3 text-slate-500 italic" x-text="item.keterangan || '-'"></td>
                              <td class="px-5 py-3 text-center space-x-2">
                                  <button @click="openDetailMasuk(item)" type="button" class="inline-flex items-center justify-center p-1.5 bg-teal-50 text-teal-600 hover:bg-teal-100 rounded-lg transition-colors" title="Detail">
                                      <i data-lucide="eye" class="w-4 h-4"></i>
                                  </button>
                                  <button @click="deleteMasuk(item.id)" type="button" class="inline-flex items-center justify-center p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus">
                                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                                  </button>
                              </td>
                          </tr>
                      </template>
                  </tbody>
              </table>
          </div>
      </div>

      <!-- Tab 2: Barang Keluar -->
      <div x-show="activeTab === 'keluar'" class="flex-1 flex flex-col space-y-6 overflow-hidden" style="display: none;">
          <!-- Sedang Keluar -->
          <div class="flex flex-col bg-white shadow-soft border border-slate-100 overflow-hidden shrink-0 transition-all duration-300"
               :class="maximizedPanel === 'active' ? 'fixed inset-4 z-50 shadow-2xl rounded-xl' : 'rounded-2xl'">
              <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-rose-50/50">
                  <h4 class="font-bold text-slate-700 text-sm">Sedang Keluar (Belum Kembali)</h4>
                  
                  <div class="flex items-center space-x-3">
                      <!-- Bulk Delete Action Active -->
                      <div class="flex items-center space-x-3 bg-white px-3 py-1.5 rounded-lg shadow-sm border transition-all" :class="selectedActive.length > 0 ? 'border-rose-200' : 'border-slate-200 opacity-60'">
                          <span class="text-xs font-bold transition-colors" :class="selectedActive.length > 0 ? 'text-rose-600' : 'text-slate-400'">
                              <span x-text="selectedActive.length"></span> Terpilih
                          </span>
                          <button @click="selectedActive.length > 0 ? bulkDeleteActive() : null" type="button" 
                                  class="px-3 py-1 rounded text-xs font-semibold shadow-sm transition-all flex items-center"
                                  :class="selectedActive.length > 0 ? 'bg-rose-500 hover:bg-rose-600 text-white cursor-pointer' : 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200'"
                                  :disabled="selectedActive.length === 0">
                              <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i> Hapus
                          </button>
                      </div>

                      <!-- Maximize Toggle -->
                      <button type="button" @click="maximizedPanel = maximizedPanel === 'active' ? null : 'active'" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-white rounded-lg transition-colors border border-transparent hover:border-slate-200 hover:shadow-sm" title="Perbesar Layar">
                          <i data-lucide="maximize-2" x-show="maximizedPanel !== 'active'" class="w-4 h-4"></i>
                          <i data-lucide="minimize-2" x-show="maximizedPanel === 'active'" x-cloak class="w-4 h-4"></i>
                      </button>
                  </div>
              </div>
              <div class="overflow-auto" :class="maximizedPanel === 'active' ? 'flex-1' : 'max-h-72'">
                  <table class="w-full text-left border-collapse whitespace-nowrap">
                      <thead>
                          <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                              <th class="px-5 py-3 font-semibold text-center w-12">
                                  <input type="checkbox" x-show="barangKeluarActive.length > 0" @change="toggleAllActive($event.target.checked)" :checked="isAllActiveSelected" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                              </th>
                              <th class="px-5 py-3 font-semibold text-center w-16">No</th>
                              <th class="px-5 py-3 font-semibold">No Dokumen</th>
                              <th class="px-5 py-3 font-semibold">Tanggal Keluar</th>
                              <th class="px-5 py-3 font-semibold text-right">Jumlah Item</th>
                              <th class="px-5 py-3 font-semibold">Keterangan</th>
                              <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-100">
                          <template x-if="barangKeluarActive.length === 0">
                              <tr>
                                  <td colspan="7" class="px-5 py-4 text-center text-sm text-slate-500 italic">Tidak ada barang yang sedang keluar.</td>
                              </tr>
                          </template>
                          <template x-for="(item, index) in barangKeluarActive" :key="index">
                              <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                  <td class="px-5 py-3 text-center">
                                      <input type="checkbox" :value="item.id" x-model="selectedActive" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                                  </td>
                                  <td class="px-5 py-3 text-center text-slate-500 font-medium" x-text="index + 1"></td>
                                  <td class="px-5 py-3 font-semibold font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                                  <td class="px-5 py-3 text-slate-600" x-text="item.tanggal"></td>
                                  <td class="px-5 py-3 text-right font-mono font-semibold text-rose-600" x-text="item.jumlah + ' Barang'"></td>
                                  <td class="px-5 py-3 text-slate-500 italic" x-text="item.keterangan || '-'"></td>
                                  <td class="px-5 py-3 text-center space-x-2">
                                      <button @click="openTerima(item)" type="button" class="inline-flex items-center justify-center px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs font-semibold transition-colors">
                                          <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1.5"></i> Terima Barang
                                      </button>
                                      <button @click="openDetail(item)" type="button" class="inline-flex items-center justify-center px-3 py-1.5 bg-teal-50 text-teal-600 hover:bg-teal-100 rounded-lg text-xs font-semibold transition-colors">
                                          <i data-lucide="eye" class="w-3.5 h-3.5 mr-1.5"></i> Detail
                                      </button>
                                      <a :href="'{{ url('workspaceinventory/mutasi') }}/' + item.id + '/print'" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition-colors">
                                          <i data-lucide="printer" class="w-3.5 h-3.5 mr-1.5"></i> Cetak BAST
                                      </a>
                                      <button @click="deleteKeluar(item.id)" type="button" class="inline-flex items-center justify-center p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus">
                                          <i data-lucide="trash-2" class="w-4 h-4"></i>
                                      </button>
                                  </td>
                              </tr>
                          </template>
                      </tbody>
                  </table>
              </div>
          </div>

          <!-- Riwayat Barang Keluar -->
          <div class="flex flex-col bg-white shadow-soft border border-slate-100 overflow-hidden transition-all duration-300"
               :class="maximizedPanel === 'history' ? 'fixed inset-4 z-50 shadow-2xl rounded-xl' : 'flex-1 rounded-2xl'">
              <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                  <h4 class="font-bold text-slate-700 text-sm">Riwayat Barang Keluar (Sudah Kembali Semua)</h4>
                  
                  <div class="flex items-center space-x-3">
                      <!-- Bulk Delete Action History -->
                      <div class="flex items-center space-x-3 bg-white px-3 py-1.5 rounded-lg shadow-sm border transition-all" :class="selectedHistory.length > 0 ? 'border-rose-200' : 'border-slate-200 opacity-60'">
                          <span class="text-xs font-bold transition-colors" :class="selectedHistory.length > 0 ? 'text-rose-600' : 'text-slate-400'">
                              <span x-text="selectedHistory.length"></span> Terpilih
                          </span>
                          <button @click="selectedHistory.length > 0 ? bulkDeleteHistory() : null" type="button" 
                                  class="px-3 py-1 rounded text-xs font-semibold shadow-sm transition-all flex items-center"
                                  :class="selectedHistory.length > 0 ? 'bg-rose-500 hover:bg-rose-600 text-white cursor-pointer' : 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200'"
                                  :disabled="selectedHistory.length === 0">
                              <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i> Hapus
                          </button>
                      </div>

                      <!-- Maximize Toggle -->
                      <button type="button" @click="maximizedPanel = maximizedPanel === 'history' ? null : 'history'" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-white rounded-lg transition-colors border border-transparent hover:border-slate-200 hover:shadow-sm" title="Perbesar Layar">
                          <i data-lucide="maximize-2" x-show="maximizedPanel !== 'history'" class="w-4 h-4"></i>
                          <i data-lucide="minimize-2" x-show="maximizedPanel === 'history'" x-cloak class="w-4 h-4"></i>
                      </button>
                  </div>
              </div>
              <div class="flex-1 overflow-auto">
                  <table class="w-full text-left border-collapse whitespace-nowrap">
                      <thead>
                          <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                              <th class="px-5 py-3 font-semibold text-center w-12">
                                  <input type="checkbox" @change="toggleAllHistory($event.target.checked)" :checked="isAllHistorySelected" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                              </th>
                              <th class="px-5 py-3 font-semibold text-center w-16">No</th>
                              <th class="px-5 py-3 font-semibold">No Dokumen</th>
                              <th class="px-5 py-3 font-semibold">Tanggal Keluar</th>
                              <th class="px-5 py-3 font-semibold text-right">Jumlah Item</th>
                              <th class="px-5 py-3 font-semibold">Keterangan</th>
                              <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-100">
                          <template x-if="barangKeluarHistory.length === 0">
                              <tr>
                                  <td colspan="7" class="px-5 py-4 text-center text-sm text-slate-500 italic">Belum ada riwayat barang keluar.</td>
                              </tr>
                          </template>
                          <template x-for="(item, index) in barangKeluarHistory" :key="index">
                              <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                  <td class="px-5 py-3 text-center">
                                      <input type="checkbox" :value="item.id" x-model="selectedHistory" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                                  </td>
                                  <td class="px-5 py-3 text-center text-slate-500 font-medium" x-text="index + 1"></td>
                                  <td class="px-5 py-3 font-semibold font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                                  <td class="px-5 py-3 text-slate-600" x-text="item.tanggal"></td>
                                  <td class="px-5 py-3 text-right font-mono font-semibold text-slate-600" x-text="item.jumlah + ' Barang'"></td>
                                  <td class="px-5 py-3 text-slate-500 italic" x-text="item.keterangan || '-'"></td>
                                  <td class="px-5 py-3 text-center space-x-2">
                                      <button @click="openDetail(item)" type="button" class="inline-flex items-center justify-center px-3 py-1.5 bg-teal-50 text-teal-600 hover:bg-teal-100 rounded-lg text-xs font-semibold transition-colors">
                                          <i data-lucide="eye" class="w-3.5 h-3.5 mr-1.5"></i> Detail
                                      </button>
                                      <a :href="'{{ url('workspaceinventory/mutasi') }}/' + item.id + '/print'" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition-colors">
                                          <i data-lucide="printer" class="w-3.5 h-3.5 mr-1.5"></i> Cetak BAST
                                      </a>
                                      <button @click="deleteKeluar(item.id)" type="button" class="inline-flex items-center justify-center p-1.5 bg-rose-50 text-rose-500 hover:bg-rose-100 rounded-lg transition-colors" title="Hapus">
                                          <i data-lucide="trash-2" class="w-4 h-4"></i>
                                      </button>
                                  </td>
                              </tr>
                          </template>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
      </div>

    <!-- MODAL TAMBAH DATA -->
    <div @open-add-modal.window="openModal = true">
        <div x-show="openModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
             style="display: none;">
            <div @click.away="openModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden">
                <form @submit.prevent="submitMutasi" class="flex flex-col w-full">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Form Pencatatan</h2>
                            <p class="text-xs text-slate-500">Isi detail barang yang masuk atau keluar.</p>
                        </div>
                        <button type="button" @click="openModal = false" class="text-slate-400 hover:text-rose-500 transition-colors p-2 hover:bg-rose-50 rounded-lg">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    
                    <div class="p-4 md:p-6 overflow-y-auto space-y-4" style="max-height: calc(100vh - 200px);">
                        <!-- Form Barang Masuk -->
                        <div x-show="formType === 'masuk'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Barang</label>
                                <input type="text" x-model="formMasuk.jenis" :required="formType === 'masuk'" placeholder="Cth: Mouse, Laptop..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Merk / Tipe</label>
                                <input type="text" x-model="formMasuk.merk" placeholder="Cth: Logitech B100..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah</label>
                                    <input type="number" x-model="formMasuk.jumlah" :required="formType === 'masuk'" min="1" placeholder="0" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Satuan</label>
                                    <input type="text" x-model="formMasuk.satuan" placeholder="Cth: Unit, Pcs..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Masuk</label>
                                <input type="date" x-model="formMasuk.tanggal" :required="formType === 'masuk'" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan (Opsional)</label>
                                <textarea rows="2" x-model="formMasuk.keterangan" placeholder="Catatan tambahan..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]"></textarea>
                            </div>
                        </div>

                        <!-- Form Barang Keluar -->
                        <div x-show="formType === 'keluar'" x-cloak class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Keluar</label>
                                <input type="date" x-model="formKeluar.tanggal" :required="formType === 'keluar'" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Barang yang Dikeluarkan</label>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-2 max-h-48 overflow-y-auto">
                                    <template x-if="isLoadingRusak">
                                        <div class="text-xs text-slate-500 text-center py-2">Memuat data barang rusak...</div>
                                    </template>
                                    <template x-if="!isLoadingRusak && inventoryRusak.length === 0">
                                        <div class="text-xs text-slate-500 text-center py-2">Tidak ada barang dengan kondisi rusak yang disimpan.</div>
                                    </template>
                                    <template x-for="inv in inventoryRusak" :key="inv.id">
                                        <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-slate-100 rounded-lg transition-colors">
                                            <input type="checkbox" :value="inv.id" x-model="formKeluar.selectedItems" class="mt-1 text-rose-500 focus:ring-rose-500 rounded border-slate-300">
                                            <div>
                                                <div class="text-sm font-semibold text-slate-700" x-text="inv.jenis + ' - ' + inv.merk"></div>
                                                <div class="text-xs text-slate-500 font-mono" x-text="'S/N: ' + inv.sn"></div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">*Hanya menampilkan barang berstatus 'Disimpan' dengan kondisi 'Rusak'.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Tujuan</label>
                                <textarea rows="2" x-model="formKeluar.alamat_tujuan" placeholder="Contoh: Jl. South Osaka Residence..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan / Alasan (Opsional)</label>
                                <textarea rows="2" x-model="formKeluar.keterangan" placeholder="Contoh: Return vendor..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end space-x-3">
                        <button type="button" @click="openModal = false" class="px-5 py-2.5 text-slate-500 hover:bg-slate-200 hover:text-slate-700 rounded-xl font-medium transition-colors text-sm">
                            Batal
                        </button>
                        <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-5 py-2.5 rounded-xl font-medium shadow-soft text-sm flex items-center">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>

    <!-- Detail Modal -->
    <div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showDetailModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="showDetailModal = false"></div>

            <div x-show="showDetailModal" x-transition class="relative inline-block w-full max-w-2xl text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Detail Barang Keluar</h3>
                        <p class="text-xs text-slate-500 mt-1" x-text="detailDoc?.doc + ' — ' + detailDoc?.tanggal"></p>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 transition-colors bg-white hover:bg-slate-100 p-2 rounded-full">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="p-4 md:p-6">
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-slate-700 mb-2">Keterangan:</h4>
                        <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100" x-text="detailDoc?.keterangan || 'Tidak ada keterangan.'"></p>
                    </div>

                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Daftar Barang (<span x-text="detailItems.length"></span> Item):</h4>
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider border-b border-slate-200">
                                    <th class="px-4 py-2 font-semibold w-12 text-center">No</th>
                                    <th class="px-4 py-2 font-semibold">Jenis / Kategori</th>
                                    <th class="px-4 py-2 font-semibold">S/N / No. Aset</th>
                                    <th class="px-4 py-2 font-semibold">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(item, index) in detailItems" :key="index">
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-2 text-center text-xs text-slate-500 font-medium" x-text="index + 1"></td>
                                        <td class="px-4 py-2 text-xs font-semibold text-slate-700" x-text="item.kategori"></td>
                                        <td class="px-4 py-2 text-xs font-mono text-[#1d4ed8]" x-text="item.no_aset"></td>
                                        <td class="px-4 py-2 text-xs text-slate-500" x-text="item.keterangan || '-'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terima Barang Modal -->
    <div x-show="showTerimaModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showTerimaModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="showTerimaModal = false"></div>

            <div x-show="showTerimaModal" x-transition class="relative inline-block w-full max-w-2xl text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8 overflow-hidden">
                <form @submit.prevent="submitTerimaKembali">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-amber-50/50">
                        <div>
                            <h3 class="text-lg font-bold text-amber-800">Terima Barang Kembali</h3>
                            <p class="text-xs text-amber-600 mt-1" x-text="terimaDoc?.doc + ' — ' + terimaDoc?.tanggal"></p>
                        </div>
                        <button type="button" @click="showTerimaModal = false" class="text-slate-400 hover:text-slate-600 transition-colors bg-white hover:bg-slate-100 p-2 rounded-full">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    
                    <div class="p-4 md:p-6">
                        <p class="text-sm text-slate-600 mb-3">Ceklis barang-barang yang sudah kembali (Diterima):</p>
                        <div class="border border-slate-200 rounded-xl overflow-hidden max-h-64 overflow-y-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider border-b border-slate-200">
                                        <th class="px-4 py-2 font-semibold w-12 text-center">Cek</th>
                                        <th class="px-4 py-2 font-semibold">Jenis / Kategori</th>
                                        <th class="px-4 py-2 font-semibold">S/N / No. Aset</th>
                                        <th class="px-4 py-2 font-semibold w-32">Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(item, index) in terimaItems" :key="index">
                                        <tr class="hover:bg-slate-50 transition-colors cursor-pointer" @click="if ($event.target.tagName !== 'SELECT') toggleTerima(item.id)">
                                            <td class="px-4 py-3 text-center">
                                                <input type="checkbox" :value="item.id" x-model="terimaSelected" class="mt-1 text-amber-500 focus:ring-amber-500 rounded border-slate-300 pointer-events-none">
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-slate-700" x-text="item.kategori"></td>
                                            <td class="px-4 py-3 text-sm font-mono text-[#1d4ed8]" x-text="item.no_aset"></td>
                                            <td class="px-4 py-3">
                                                <select x-model="terimaKondisi[item.id]" class="w-full px-2 py-1 text-xs rounded border border-slate-200 text-slate-700 outline-none focus:ring-1 focus:ring-amber-500" @click.stop>
                                                    <option value="Baik">Baik</option>
                                                    <option value="Rusak">Rusak</option>
                                                    <option value="Hilang">Hilang</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end space-x-3">
                        <button type="button" @click="showTerimaModal = false" class="px-5 py-2.5 text-slate-500 hover:bg-slate-200 hover:text-slate-700 rounded-xl font-medium transition-colors text-sm">
                            Batal
                        </button>
                        <button type="submit" class="bg-gradient-to-br from-amber-500 to-amber-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-soft text-sm flex items-center">
                            <i data-lucide="check" class="w-4 h-4 mr-2"></i> Konfirmasi Penerimaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MODAL DETAIL BARANG MASUK -->
    <div x-show="showDetailMasukModal" 
         class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="showDetailMasukModal = false" class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-teal-50/50">
                <h3 class="text-lg font-bold text-teal-800">Detail Barang Masuk</h3>
                <button type="button" @click="showDetailMasukModal = false" class="text-slate-400 hover:text-slate-600 transition-colors bg-white hover:bg-slate-100 p-2 rounded-full">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jenis Barang</p>
                            <p class="text-sm font-semibold text-slate-800" x-text="detailMasukDoc ? detailMasukDoc.jenis : '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Merk / Tipe</p>
                            <p class="text-sm font-medium text-slate-700" x-text="detailMasukDoc ? detailMasukDoc.merk : '-'"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jumlah</p>
                            <p class="text-sm font-mono font-semibold text-[#1d4ed8]" x-text="detailMasukDoc ? detailMasukDoc.jumlah + ' ' + (detailMasukDoc.satuan || '') : '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tanggal Masuk</p>
                            <p class="text-sm font-medium text-slate-700" x-text="detailMasukDoc ? detailMasukDoc.tanggal : '-'"></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Keterangan</p>
                        <p class="text-sm text-slate-600 italic bg-slate-50 p-3 rounded-xl border border-slate-100" x-text="detailMasukDoc && detailMasukDoc.keterangan ? detailMasukDoc.keterangan : 'Tidak ada keterangan'"></p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="button" @click="showDetailMasukModal = false" class="px-5 py-2.5 bg-slate-800 text-white font-semibold text-sm rounded-xl hover:bg-slate-900 transition-colors shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- PIN Modal for Delete -->
    <div x-show="openPinModal" 
         class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;" x-cloak>
        <div @click.away="openPinModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-fade-in text-center p-4 md:p-6">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="lock" class="w-8 h-8 text-rose-500"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Keamanan Penghapusan</h3>
            <p class="text-sm text-slate-500 mb-4">Masukkan 6-digit PIN untuk menghapus <span class="font-bold text-slate-700" x-text="(deleteMode === 'bulkActive' ? selectedActive.length : (deleteMode === 'bulkHistory' ? selectedHistory.length : '1')) + ' data'"></span>.</p>
            
            <input type="password" x-model="pinInput" placeholder="••••••" class="w-full text-center tracking-[1em] text-xl font-bold px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition-all mb-2" maxlength="6">
            
            <p x-show="pinError" x-text="pinError" class="text-rose-500 text-xs font-bold mb-4 h-4"></p>
            
            <div class="flex space-x-3 mt-4">
                <button type="button" @click="openPinModal = false; pinInput = ''; pinError = ''" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="button" @click="processDelete()" :disabled="deleteProcessing" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 transition-colors shadow-soft disabled:opacity-50 flex justify-center items-center">
                    <span x-show="!deleteProcessing">Konfirmasi</span>
                    <i x-show="deleteProcessing" data-lucide="loader-2" class="w-4 h-4 animate-spin" style="display: none;"></i>
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
          openModal: false,
          barangMasuk: {!! json_encode($masuk->map(fn($m) => [
              'id' => $m->id,
              'jenis' => $m->jenis,
              'merk' => $m->merk,
              'jumlah' => $m->jumlah,
              'satuan' => $m->satuan,
              'tanggal' => \Carbon\Carbon::parse($m->created_at)->format('d M Y'),
              'keterangan' => $m->keterangan
          ])) !!},
          barangKeluarActive: {!! json_encode($keluarActive->map(fn($k) => [
              'id' => $k->id,
              'doc' => $k->doc_number,
              'tanggal' => \Carbon\Carbon::parse($k->created_at)->format('d M Y'),
              'jumlah' => $k->items->count(),
              'keterangan' => $k->keterangan,
              'items' => $k->items->map(fn($i) => [
                  'id' => $i->id,
                  'kategori' => $i->kategori,
                  'no_aset' => $i->no_aset,
                  'keterangan' => $i->keterangan,
                  'is_returned' => $i->is_returned
              ])->toArray()
          ])) !!},
          barangKeluarHistory: {!! json_encode($keluarHistory->map(fn($k) => [
              'id' => $k->id,
              'doc' => $k->doc_number,
              'tanggal' => \Carbon\Carbon::parse($k->created_at)->format('d M Y'),
              'jumlah' => $k->items->count(),
              'keterangan' => $k->keterangan,
              'items' => $k->items->map(fn($i) => [
                  'id' => $i->id,
                  'kategori' => $i->kategori,
                  'no_aset' => $i->no_aset,
                  'keterangan' => $i->keterangan,
                  'is_returned' => $i->is_returned
              ])->toArray()
          ])) !!},
          
          selectedMasuk: [],
          selectedActive: [],
          selectedHistory: [],
          maximizedPanel: null,
          
          openPinModal: false,
          pinInput: '',
          pinError: '',
          deleteProcessing: false,
          deleteMode: 'single',
          deleteId: null,

          get isAllMasukSelected() {
              return this.barangMasuk.length > 0 && this.barangMasuk.every(i => this.selectedMasuk.includes(i.id.toString()) || this.selectedMasuk.includes(i.id));
          },

          get isAllActiveSelected() {
              return this.barangKeluarActive.length > 0 && this.barangKeluarActive.every(i => this.selectedActive.includes(i.id.toString()) || this.selectedActive.includes(i.id));
          },

          get isAllHistorySelected() {
              return this.barangKeluarHistory.length > 0 && this.barangKeluarHistory.every(i => this.selectedHistory.includes(i.id.toString()) || this.selectedHistory.includes(i.id));
          },

          toggleAllMasuk(checked) {
              if (checked) {
                  this.barangMasuk.forEach(i => {
                      if (!this.selectedMasuk.includes(i.id.toString()) && !this.selectedMasuk.includes(i.id)) {
                          this.selectedMasuk.push(i.id);
                      }
                  });
              } else {
                  this.selectedMasuk = this.selectedMasuk.filter(id => !this.barangMasuk.some(i => i.id == id));
              }
          },

          toggleAllActive(checked) {
              if (checked) {
                  this.barangKeluarActive.forEach(i => {
                      if (!this.selectedActive.includes(i.id.toString()) && !this.selectedActive.includes(i.id)) {
                          this.selectedActive.push(i.id);
                      }
                  });
              } else {
                  this.selectedActive = this.selectedActive.filter(id => !this.barangKeluarActive.some(i => i.id == id));
              }
          },

          toggleAllHistory(checked) {
              if (checked) {
                  this.barangKeluarHistory.forEach(i => {
                      if (!this.selectedHistory.includes(i.id.toString()) && !this.selectedHistory.includes(i.id)) {
                          this.selectedHistory.push(i.id);
                      }
                  });
              } else {
                  this.selectedHistory = this.selectedHistory.filter(id => !this.barangKeluarHistory.some(i => i.id == id));
              }
          },

          showDetailModal: false,
          detailDoc: null,
          detailItems: [],
          showDetailMasukModal: false,
          detailMasukDoc: null,

          openDetail(item) {
              this.detailDoc = item;
              this.detailItems = item.items || [];
              this.showDetailModal = true;
          },

          showTerimaModal: false,
          terimaDoc: null,
          terimaItems: [],
          terimaSelected: [],
          terimaKondisi: {},

          openTerima(item) {
              this.terimaDoc = item;
              // Hanya tampilkan barang yang belum kembali
              this.terimaItems = (item.items || []).filter(i => !i.is_returned);
              this.terimaSelected = [];
              this.terimaKondisi = {};
              this.terimaItems.forEach(i => {
                  this.terimaKondisi[i.id] = 'Baik'; // Default kondisi
              });
              this.showTerimaModal = true;
          },

          toggleTerima(id) {
              const index = this.terimaSelected.indexOf(id);
              if (index > -1) {
                  this.terimaSelected.splice(index, 1);
              } else {
                  this.terimaSelected.push(id);
              }
          },

          formType: 'masuk',
          formMasuk: {
              jenis: '',
              merk: '',
              jumlah: '',
              satuan: '',
              tanggal: '{{ date('Y-m-d') }}',
              keterangan: ''
          },
          formKeluar: {
              tanggal: '{{ date('Y-m-d') }}',
              alamat_tujuan: '',
              keterangan: '',
              selectedItems: []
          },
          inventoryRusak: [],
          isLoadingRusak: false,

          init() {
              this.$watch('formType', (value) => {
                  if (value === 'keluar' && this.inventoryRusak.length === 0) {
                      this.fetchInventoryRusak();
                  }
              });
          },

          async fetchInventoryRusak() {
              this.isLoadingRusak = true;
              try {
                  const res = await fetch('{{ route('mutasi.api-rusak') }}');
                  const json = await res.json();
                  if (json.success) {
                      this.inventoryRusak = json.data;
                  }
              } catch (e) {
                  console.error(e);
              }
              this.isLoadingRusak = false;
          },

          async submitMutasi() {
              if (this.formType === 'masuk') {
                  const formData = new FormData();
                  formData.append('_token', '{{ csrf_token() }}');
                  for (const key in this.formMasuk) {
                      formData.append(key, this.formMasuk[key]);
                  }
                  
                  try {
                      const res = await fetch('{{ route('mutasi.masuk') }}', { method: 'POST', body: formData });
                      const json = await res.json();
                      if (json.success) {
                          Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, showConfirmButton: false, timer: 3000 }).then(() => {
                              window.location.reload();
                          });
                      } else {
                          Swal.fire({ icon: 'error', title: 'Gagal!', text: json.message || 'Terjadi kesalahan', confirmButtonColor: '#1d4ed8' });
                      }
                  } catch (e) {
                      Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyambung ke server', confirmButtonColor: '#1d4ed8' });
                  }
              } else {
                  if (this.formKeluar.selectedItems.length === 0) {
                      Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Pilih minimal 1 barang yang akan dikeluarkan!', confirmButtonColor: '#1d4ed8' });
                      return;
                  }
                  const formData = new FormData();
                  formData.append('_token', '{{ csrf_token() }}');
                  formData.append('tanggal', this.formKeluar.tanggal);
                  formData.append('alamat_tujuan', this.formKeluar.alamat_tujuan);
                  formData.append('keterangan', this.formKeluar.keterangan);
                  this.formKeluar.selectedItems.forEach(id => {
                      formData.append('items[]', id);
                  });
                  try {
                      const res = await fetch('{{ route('mutasi.keluar') }}', { method: 'POST', body: formData });
                      const json = await res.json();
                      if (json.success) {
                          Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, showConfirmButton: false, timer: 3000 }).then(() => {
                              window.location.reload();
                          });
                      } else {
                          Swal.fire({ icon: 'error', title: 'Gagal!', text: json.message || 'Terjadi kesalahan', confirmButtonColor: '#1d4ed8' });
                      }
                  } catch (e) {
                      Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyambung ke server', confirmButtonColor: '#1d4ed8' });
                  }
              }
          },

          async submitTerimaKembali() {
              if (this.terimaSelected.length === 0) {
                  Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Pilih minimal 1 barang yang diterima!', confirmButtonColor: '#1d4ed8' });
                  return;
              }
              
              const formData = new FormData();
              formData.append('_token', '{{ csrf_token() }}');
              this.terimaSelected.forEach(id => {
                  formData.append('returned_items[]', id);
                  formData.append('returned_conditions[' + id + ']', this.terimaKondisi[id] || 'Baik');
              });

              try {
                  const url = '{{ url('workspaceinventory/mutasi') }}/' + this.terimaDoc.id + '/terima';
                  const res = await fetch(url, { method: 'POST', body: formData });
                  const json = await res.json();
                  if (json.success) {
                      this.showTerimaModal = false;
                      Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, showConfirmButton: false, timer: 3000 }).then(() => {
                          window.location.reload();
                      });
                  } else {
                      Swal.fire({ icon: 'error', title: 'Gagal!', text: json.message || 'Terjadi kesalahan', confirmButtonColor: '#1d4ed8' });
                  }
              } catch (e) {
                  Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyambung ke server', confirmButtonColor: '#1d4ed8' });
              }
          },

          openDetailMasuk(item) {
              this.detailMasukDoc = item;
              this.showDetailMasukModal = true;
          },

          deleteMasuk(id) {
              this.deleteId = id;
              this.deleteMode = 'singleMasuk';
              this.pinInput = '';
              this.pinError = '';
              this.openPinModal = true;
          },

          bulkDeleteMasuk() {
              if (this.selectedMasuk.length === 0) return;
              this.deleteMode = 'bulkMasuk';
              this.pinInput = '';
              this.pinError = '';
              this.openPinModal = true;
          },

          deleteKeluar(id) {
              this.deleteId = id;
              this.deleteMode = 'single';
              this.pinInput = '';
              this.pinError = '';
              this.openPinModal = true;
          },

          bulkDeleteActive() {
              if (this.selectedActive.length === 0) return;
              this.deleteMode = 'bulkActive';
              this.pinInput = '';
              this.pinError = '';
              this.openPinModal = true;
          },

          bulkDeleteHistory() {
              if (this.selectedHistory.length === 0) return;
              this.deleteMode = 'bulkHistory';
              this.pinInput = '';
              this.pinError = '';
              this.openPinModal = true;
          },

          async processDelete() {
              this.pinError = '';
              if (!this.pinInput) {
                        this.pinError = 'PIN harus diisi!';
                        return;
                    }
              this.deleteProcessing = true;

              try {
                  let res, json;
                  
                  if (this.deleteMode === 'singleMasuk') {
                      res = await fetch('{{ url('workspaceinventory/mutasi/masuk') }}/' + this.deleteId, {
                          method: 'DELETE',
                          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                      });
                  } else if (this.deleteMode === 'bulkMasuk') {
                      res = await fetch('{{ route('mutasi.bulk-destroy-masuk') }}', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                          body: JSON.stringify({ ids: this.selectedMasuk })
                      });
                  } else if (this.deleteMode === 'single') {
                      res = await fetch('{{ url('workspaceinventory/mutasi/keluar') }}/' + this.deleteId, {
                          method: 'DELETE',
                          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                      });
                  } else {
                      const ids = this.deleteMode === 'bulkActive' ? this.selectedActive : this.selectedHistory;
                      res = await fetch('{{ route('mutasi.bulk-destroy-keluar') }}', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                          body: JSON.stringify({ ids: ids })
                      });
                  }
                  
                  json = await res.json();
                  
                  if (json.success) {
                      Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, showConfirmButton: false, timer: 3000 }).then(() => {
                          window.location.reload();
                      });
                  } else {
                      this.pinError = json.message || 'Gagal menghapus data';
                      this.deleteProcessing = false;
                  }
              } catch (e) {
                  this.pinError = 'Gagal menyambung ke server';
                  this.deleteProcessing = false;
              }
          }
        }
      }

      document.addEventListener('alpine:init', () => {
          Alpine.data('mutasiApp', () => mutasiApp());
      });
    </script>
    @endpush
</x-layout>
