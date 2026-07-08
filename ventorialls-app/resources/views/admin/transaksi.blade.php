<x-layout active="transaksi" headerTitle="Manajemen Transaksi">
    <div x-data="transaksiApp" class="flex flex-col h-full">
      
      <!-- Top Action Bar -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
          <!-- Tabs Navigation -->
          <div class="flex flex-wrap gap-2">
            <button @click="activeTab = 'serah-terima'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'serah-terima' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'serah-terima' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600 group-hover:bg-blue-200'">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                </div>
                Serah Terima
            </button>
            <button @click="activeTab = 'peminjaman'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'peminjaman' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'peminjaman' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-600 group-hover:bg-amber-200'">
                    <i data-lucide="corner-up-right" class="w-4 h-4"></i>
                </div>
                Peminjaman
            </button>
            <button @click="activeTab = 'penukaran'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'penukaran' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'penukaran' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200'">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </div>
                Penukaran
            </button>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
              <a :href="'{{ route('admin-tiket') }}?type=' + activeTab" class="bg-white border border-slate-200 text-[#1d4ed8] hover:bg-blue-50 px-5 py-2.5 rounded-xl font-medium shadow-sm transition-all flex items-center text-sm shrink-0">
                  <i data-lucide="ticket" class="w-4 h-4 mr-2"></i>
                  <span>Manajemen Tiket</span>
              </a>
              <button @click="openModal = true" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-5 py-2.5 rounded-xl font-medium shadow-soft shadow-[#1d4ed8]/30 transition-all flex items-center text-sm shrink-0">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                <span x-text="'Buat ' + getTabName()"></span>
              </button>
          </div>
      </div>

      <!-- Main Container -->
      <div class="bg-white rounded-2xl shadow-soft border border-slate-100 flex-1 overflow-hidden flex flex-col">
          
          <!-- Search Bar & Filters -->
          <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 bg-white">
              <div class="flex items-center gap-3 w-full sm:w-auto">
                  <div class="relative w-full sm:w-80">
                  <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                  <input type="text" x-model="searchQuery" placeholder="Cari Dokumen atau Nama..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm">
                  </div>
              </div>
          <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
              <!-- Filter Tanggal (Khusus Serah Terima) -->
              <input x-show="activeTab === 'serah-terima'" type="date" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 outline-none text-xs font-medium cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">

              <!-- Filter Kategori Dinamis -->
              <select x-show="activeTab !== 'serah-terima'" x-model="filterKategori" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 outline-none text-xs font-medium cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">
                  <option value="semua">Semua Kategori</option>
                  
                  <!-- Opsi Filter untuk Peminjaman -->
                  <template x-if="activeTab === 'peminjaman'">
                      <option value="dipinjam">Status: Sedang Dipinjam</option>
                  </template>
                  <template x-if="activeTab === 'peminjaman'">
                      <option value="dikembalikan">Status: Sudah Dikembalikan</option>
                  </template>
                  <template x-if="activeTab === 'peminjaman'">
                      <option value="offboarding">Status: Offboarding</option>
                  </template>
              </select>
              <button @click="openDeletePinModal('bulk')" :disabled="selectedIds.length === 0" class="px-3 py-2 rounded-lg text-xs font-medium transition-all shadow-sm flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" :class="selectedIds.length > 0 ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200' : 'bg-slate-50 text-slate-400 border border-slate-200'">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i>
                  Hapus <span x-show="selectedIds.length > 0" x-text="'(' + selectedIds.length + ')'" class="ml-1"></span>
              </button>
              <button class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 flex items-center justify-center text-xs font-medium transition-colors shadow-sm">
                  <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5 text-slate-400"></i>
                  Export
              </button>
          </div>
      </div>

      <!-- Main Data Tables -->
      <div class="flex-1 overflow-hidden flex flex-col bg-white">
          
        <!-- 1. Serah Terima Table -->
        <div x-show="activeTab === 'serah-terima'" class="flex-1 overflow-auto" style="display: none;">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                        <th class="px-5 py-3 w-10 text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" @change="toggleAll" :checked="allSelected">
                        </th>
                        <th class="px-5 py-3 font-semibold">No. Dokumen</th>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Penerima</th>
                        <th class="px-5 py-3 font-semibold">Department</th>
                        <th class="px-5 py-3 font-semibold text-center">Total Item</th>
                        <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="item in serahTerimaList" :key="item.doc">
                        <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                            <td class="px-5 py-3 text-center">
                                <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" :value="item.id" x-model="selectedIds">
                            </td>
                            <td class="px-5 py-3 font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                            <td class="px-5 py-3" x-text="item.tanggal"></td>
                            <td class="px-5 py-3 font-medium text-slate-700" x-text="item.penerima"></td>
                            <td class="px-5 py-3" x-text="item.dept"></td>
                            <td class="px-5 py-3 text-center" 
                                x-data="{ showTooltip: false, mouseX: 0, mouseY: 0 }" 
                                @mouseenter="showTooltip = true" 
                                @mouseleave="showTooltip = false" 
                                @mousemove="mouseX = $event.clientX; mouseY = $event.clientY">
                                <button @click="selectedTx = item; showDetailModal = true" class="flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold w-16 mx-auto hover:bg-blue-50 hover:text-[#1d4ed8] hover:border-blue-200 transition-colors shadow-sm cursor-pointer group">
                                    <i data-lucide="box" class="w-3.5 h-3.5 mr-1.5 text-slate-400 group-hover:text-[#1d4ed8]"></i>
                                    <span x-text="item.items"></span>
                                </button>
                                
                                <!-- Hover Tooltip (Teleported to body to avoid overflow clipping) -->
                                <template x-teleport="body">
                                    <div x-show="showTooltip" 
                                         x-cloak 
                                         :style="`left: ${mouseX + 15}px; top: ${mouseY + 15}px;`" 
                                         class="fixed z-[100] bg-white rounded-xl shadow-md border border-slate-100 p-3 min-w-[150px] pointer-events-none transition-opacity duration-150">
                                        <div class="font-bold border-b border-slate-100 pb-2 mb-2 text-slate-800 text-xs" x-text="'Daftar Aset (' + item.items + ')'"></div>
                                        <div class="flex flex-col gap-1.5">
                                            <template x-for="detail in item.items_data" :key="detail.no_aset">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-emerald-500 mr-2 flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                                    <span class="font-mono text-xs text-slate-700 font-semibold" x-text="detail.no_aset"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </td>
                            <td class="px-5 py-3 text-center flex items-center justify-center space-x-2">
                                <button @click="selectedTx = item; showDetailModal = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <a :href="'/workspaceinventory/transaksi/' + item.id + '/print'" target="_blank" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Cetak BAST">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>
                                <button @click="openDeletePinModal('single', item)" class="text-slate-400 hover:text-rose-600 transition-colors p-1" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- 2. Peminjaman Table -->
        <div x-show="activeTab === 'peminjaman'" class="flex-1 flex flex-col overflow-hidden" style="display: none;">
            <!-- Sub-Menu Peminjaman -->
            <div class="px-5 py-3 border-b border-slate-100 flex items-center space-x-2 bg-slate-50/50">
                <button @click="subTabPeminjaman = 'aktif_dalam'" class="px-4 py-1.5 rounded-lg font-semibold text-xs transition-colors" :class="subTabPeminjaman === 'aktif_dalam' ? 'bg-[#1d4ed8] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'">
                    Pinjam Dalam
                </button>
                <button @click="subTabPeminjaman = 'aktif_luar'" class="px-4 py-1.5 rounded-lg font-semibold text-xs transition-colors" :class="subTabPeminjaman === 'aktif_luar' ? 'bg-[#1d4ed8] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'">
                    Pinjam Luar
                </button>
                <button @click="subTabPeminjaman = 'riwayat'" class="px-4 py-1.5 rounded-lg font-semibold text-xs transition-colors" :class="subTabPeminjaman === 'riwayat' ? 'bg-[#1d4ed8] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'">
                    Riwayat
                </button>
            </div>

            <!-- Sub-Tab: Aktif -->
            <div x-show="subTabPeminjaman === 'aktif_dalam' || subTabPeminjaman === 'aktif_luar'" class="flex-1 overflow-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3 w-10 text-center">
                                <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" @change="toggleAll" :checked="allSelected">
                            </th>
                            <th class="px-5 py-3 font-semibold">Nama Peminjam</th>
                            <th class="px-5 py-3 font-semibold">Departemen</th>
                            <th class="px-5 py-3 font-semibold text-center">Total Barang Aktif</th>
                            <th class="px-5 py-3 font-semibold text-center">Riwayat</th>
                            <th class="px-5 py-3 font-semibold text-center">Aksi (Retur)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="item in pinjamList.filter(i => subTabPeminjaman === 'aktif_dalam' ? i.pinjam_dalam > 0 : i.pinjam_luar > 0)" :key="item.id">
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-5 py-3 text-center">
                                    <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" :value="item.id" x-model="selectedIds">
                                </td>
                                <td class="px-5 py-3 font-bold text-slate-800 flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs mr-3" x-text="item.peminjam.charAt(0).toUpperCase()"></div>
                                    <span x-text="item.peminjam"></span>
                                </td>
                                <td class="px-5 py-3 text-slate-600" x-text="item.dept"></td>
                                <td class="px-5 py-3 text-center" 
                                    x-data="{ showTooltip: false, mouseX: 0, mouseY: 0 }" 
                                    @mouseenter="showTooltip = true" 
                                    @mouseleave="showTooltip = false" 
                                    @mousemove="mouseX = $event.clientX; mouseY = $event.clientY">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="selectedTx = item; showHistoryModal = true" class="flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold w-16 mx-auto hover:bg-blue-50 hover:text-[#1d4ed8] hover:border-blue-200 transition-colors shadow-sm cursor-pointer group">
                                            <i data-lucide="box" class="w-3.5 h-3.5 mr-1.5 text-slate-400 group-hover:text-[#1d4ed8]"></i>
                                            <span x-text="subTabPeminjaman === 'aktif_dalam' ? item.pinjam_dalam : item.pinjam_luar"></span>
                                        </button>
                                    </div>
                                    
                                    <!-- Hover Tooltip -->
                                    <template x-teleport="body">
                                        <div x-show="showTooltip && (subTabPeminjaman === 'aktif_dalam' ? item.pinjam_dalam : item.pinjam_luar) > 0" 
                                             x-cloak 
                                             :style="`left: ${mouseX + 15}px; top: ${mouseY + 15}px;`" 
                                             class="fixed z-[100] bg-white rounded-xl shadow-md border border-slate-100 p-3 min-w-[150px] pointer-events-none transition-opacity duration-150">
                                            <div class="font-bold border-b border-slate-100 pb-2 mb-2 text-slate-800 text-xs" x-text="'Daftar Aset (' + (subTabPeminjaman === 'aktif_dalam' ? item.pinjam_dalam : item.pinjam_luar) + ')'"></div>
                                            <div class="flex flex-col gap-1.5">
                                                <template x-for="detail in item.items_data.filter(d => subTabPeminjaman === 'aktif_dalam' ? !d.hak_bawa_pulang : d.hak_bawa_pulang)" :key="detail.no_aset">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <div class="flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-[#1d4ed8] mr-2 flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                                            <span class="font-mono text-xs text-slate-700 font-semibold" x-text="detail.no_aset"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <button @click="selectedTx = item; showHistoryModal = true" class="text-slate-500 hover:text-[#1d4ed8] transition-colors p-1.5 rounded-lg hover:bg-blue-50 flex items-center mx-auto space-x-1" title="Lihat Riwayat & Detail">
                                        <i data-lucide="history" class="w-4 h-4"></i>
                                        <span class="text-xs font-semibold">Detail</span>
                                    </button>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <template x-if="item.items_data.length > 0">
                                        <button @click="selectedTx = item; openReturnModal = true" class="inline-flex items-center text-emerald-600 hover:bg-emerald-50 rounded-lg px-2.5 py-1 transition-colors text-xs font-bold border border-emerald-200 bg-white shadow-sm" title="Kembalikan Barang">
                                            <i data-lucide="corner-down-left" class="w-3.5 h-3.5 mr-1"></i> Retur
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Sub-Tab: Riwayat -->
            <div x-show="subTabPeminjaman === 'riwayat'" class="flex-1 overflow-auto" style="display: none;">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold">No. Dokumen</th>
                            <th class="px-5 py-3 font-semibold">Tanggal</th>
                            <th class="px-5 py-3 font-semibold">Peminjam</th>
                            <th class="px-5 py-3 font-semibold">Jenis</th>
                            <th class="px-5 py-3 font-semibold text-center">Item Terkait</th>
                            <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="item in historyPeminjamanList" :key="item.id">
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-5 py-3 font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                                <td class="px-5 py-3" x-text="item.tgl"></td>
                                <td class="px-5 py-3 font-bold text-slate-700" x-text="item.peminjam"></td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600" x-text="item.type.replace('_', ' ')"></span>
                                </td>
                                <td class="px-5 py-3 text-center" 
                                    x-data="{ showTooltip: false, mouseX: 0, mouseY: 0 }" 
                                    @mouseenter="showTooltip = true" 
                                    @mouseleave="showTooltip = false" 
                                    @mousemove="mouseX = $event.clientX; mouseY = $event.clientY">
                                    <button @click="selectedTx = item; showDetailModal = true" class="flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold w-16 mx-auto hover:bg-blue-50 hover:text-[#1d4ed8] hover:border-blue-200 transition-colors shadow-sm cursor-pointer group">
                                        <i data-lucide="box" class="w-3.5 h-3.5 mr-1.5 text-slate-400 group-hover:text-[#1d4ed8]"></i>
                                        <span x-text="item.items_data.length"></span>
                                    </button>
                                    
                                    <!-- Hover Tooltip -->
                                    <template x-teleport="body">
                                        <div x-show="showTooltip && item.items_data.length > 0" 
                                             x-cloak 
                                             :style="`left: ${mouseX + 15}px; top: ${mouseY + 15}px;`" 
                                             class="fixed z-[100] bg-white rounded-xl shadow-md border border-slate-100 p-3 min-w-[150px] pointer-events-none transition-opacity duration-150">
                                            <div class="font-bold border-b border-slate-100 pb-2 mb-2 text-slate-800 text-xs" x-text="'Daftar Aset (' + item.items_data.length + ')'"></div>
                                            <div class="flex flex-col gap-1.5">
                                                <template x-for="detail in item.items_data" :key="detail.no_aset">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-emerald-500 mr-2 flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                                        <span class="font-mono text-xs text-slate-700 font-semibold" x-text="detail.no_aset"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-5 py-3 text-center flex items-center justify-center space-x-2">
                                    <button @click="selectedTx = item; showDetailModal = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Detail Dokumen">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <a :href="'/workspaceinventory/transaksi/' + item.id + '/print'" target="_blank" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Cetak BAST">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </a>
                                    <button @click="openDeletePinModal('single', item)" class="text-slate-400 hover:text-rose-600 transition-colors p-1" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Penukaran Table -->
        <div x-show="activeTab === 'penukaran'" class="flex-1 overflow-auto" style="display: none;">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                        <th class="px-5 py-3 w-10 text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" @change="toggleAll" :checked="allSelected">
                        </th>
                        <th class="px-5 py-3 font-semibold">No. Transaksi</th>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Pengguna</th>
                        <th class="px-5 py-3 font-semibold text-rose-600">Barang Masuk (Rusak)</th>
                        <th class="px-5 py-3 font-semibold text-emerald-600">Barang Keluar (Baru)</th>
                        <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="item in penukaranList" :key="item.doc">
                        <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                            <td class="px-5 py-3 text-center">
                                <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" :value="item.id" x-model="selectedIds">
                            </td>
                            <td class="px-5 py-3 font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                            <td class="px-5 py-3" x-text="item.tanggal"></td>
                            <td class="px-5 py-3 font-medium text-slate-700" x-text="item.pengguna"></td>
                            <td class="px-5 py-3 font-mono text-xs text-rose-600 bg-rose-50/50" x-text="item.sn_masuk"></td>
                            <td class="px-5 py-3 font-mono text-xs text-emerald-600 bg-emerald-50/50" x-text="item.sn_keluar"></td>
                            <td class="px-5 py-3 text-center flex items-center justify-center space-x-2">
                                <button @click="selectedTx = item; showDetailModal = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <a :href="'/workspaceinventory/transaksi/' + item.id + '/print'" target="_blank" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Cetak BAST">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>
                                <button @click="openDeletePinModal('single', item)" class="text-slate-400 hover:text-rose-600 transition-colors p-1" title="Hapus">
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

    <!-- CREATE TRANSACTION MODAL -->
    <div x-show="openModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openModal = false" class="bg-white w-full max-w-3xl max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl flex-shrink-0">
                <h3 class="font-bold text-slate-800" x-text="'Buat Transaksi: ' + getTabName()"></h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="flex-1 overflow-auto p-6">
                
                <!-- Success Message Popup -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="fixed top-4 right-4 z-[60] bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-3">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span class="font-semibold">{{ session('success') }}</span>
                    <button type="button" @click="show = false" class="text-white hover:text-emerald-200 ml-4"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
                @endif

                <!-- FORM: Serah Terima -->
                <form x-show="activeTab === 'serah-terima'" action="{{ route('transaksi.serah-terima') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center pb-2 border-b border-slate-100">
                            <i data-lucide="user-plus" class="w-4 h-4 mr-2 text-blue-600"></i>
                            Input Data Karyawan Baru
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="karyawan[nama]" placeholder="Nama lengkap..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">No WA Aktif</label>
                                <input type="text" name="karyawan[no_wa]" placeholder="08..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Team Leader</label>
                                <input type="text" name="karyawan[team_leader]" placeholder="Team Leader..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">No KTP</label>
                                <input type="text" name="karyawan[nik_ktp]" placeholder="NIK KTP..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Sesuai KTP</label>
                                <input type="text" name="karyawan[alamat_ktp]" placeholder="Alamat lengkap KTP..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Domisili Saat Ini</label>
                                <input type="text" name="karyawan[domisili]" placeholder="Alamat domisili..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Divisi / Department</label>
                                <select name="karyawan[department]" class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm" required>
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
                                <select name="karyawan[ruangan]" class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm" required>
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
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3 flex items-center justify-between">
                            <span>Data Aset & Bukti Fisik <span class="text-rose-500">*</span></span>
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
                                    <input type="text" name="items[{{ $key }}][no_aset]"
                                        placeholder="Input No. Aset {{ $label }}..."
                                        class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-all">
                                    <!-- Hidden foto input -->
                                    <input type="file" name="items[{{ $key }}][foto]" accept="image/*" capture="environment"
                                        class="hidden" id="cam-st-{{ $key }}">
                                    <button type="button" onclick="document.getElementById('cam-st-{{ $key }}').click()"
                                        title="Foto No. Aset"
                                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm border border-slate-200">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm">Simpan Transaksi</button>
                    </div>
                </form>

                <!-- FORM: Peminjaman -->
                <div x-show="activeTab === 'peminjaman'" x-data="peminjamanApp()" class="space-y-6">
                    <form id="form-peminjaman" action="{{ route('transaksi.peminjaman') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <!-- Hidden: karyawan_id (diisi setelah pencarian) -->
                    <input type="hidden" name="karyawan_id" x-model="karyawanId">

                    <!-- Tipe Peminjaman -->
                    <div class="flex space-x-4 mb-4 border-b border-slate-100 pb-4">
                        <label class="flex items-center space-x-2 cursor-pointer border border-slate-200 bg-slate-50 px-4 py-2.5 rounded-xl transition-all" :class="borrowType === 'dalam' ? 'border-[#1d4ed8] bg-blue-50/50' : ''">
                            <input type="radio" name="borrow_type" x-model="borrowType" value="dalam" class="text-[#1d4ed8]">
                            <span class="text-sm font-semibold text-slate-700">Internal (Lingkup PTMPTB)</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer border border-slate-200 bg-slate-50 px-4 py-2.5 rounded-xl transition-all" :class="borrowType === 'luar' ? 'border-indigo-400 bg-indigo-50/50' : ''">
                            <input type="radio" name="borrow_type" x-model="borrowType" value="luar" class="text-indigo-600">
                            <span class="text-sm font-semibold text-slate-700">Eksternal (Bawa Pulang)</span>
                        </label>
                    </div>

                    <div x-show="borrowType === 'dalam'" class="bg-blue-50/50 p-3 rounded-lg text-xs text-blue-700 mb-2 border border-blue-100 flex items-start">
                        <i data-lucide="info" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i> Alat hanya digunakan di dalam lingkungan kantor PTMPTB.
                    </div>
                    <div x-show="borrowType === 'luar'" class="bg-indigo-50/50 p-3 rounded-lg text-xs text-indigo-700 mb-2 border border-indigo-100 flex items-start">
                        <i data-lucide="alert-triangle" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i> Peringatan: Alat dibawa keluar PTMPTB. BAST Ekstra akan dicetak.
                    </div>

                    <!-- Pencarian Karyawan -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Karyawan</label>
                        <div class="flex space-x-2">
                            <input type="text" x-model="cariNama" @keyup.enter.prevent="cariKaryawan()" @keydown.enter.prevent placeholder="Cari nama karyawan..." class="flex-1 px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            <button type="button" @click="cariKaryawan()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium transition-colors">Cari</button>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Jika belum ada, tambahkan dulu di menu Karyawan.</p>
                    </div>

                    <!-- Hasil: Ditemukan -->
                    <div x-show="karyawanFound" class="mb-4 border border-emerald-100 bg-emerald-50/30 rounded-xl p-4" style="display:none;">
                        <h4 class="text-sm font-bold text-slate-800 mb-1 flex items-center">
                            <i data-lucide="user-check" class="w-4 h-4 mr-2 text-emerald-600"></i>
                            Karyawan: <span class="ml-1 font-mono text-emerald-700" x-text="karyawanNama"></span>
                        </h4>
                        <p class="text-xs text-slate-500 mb-3">Department: <span x-text="karyawanDept"></span></p>

                        <!-- List Barang Dipinjam -->
                        <div x-show="karyawanItems.length > 0" class="mt-3 pt-3 border-t border-emerald-100">
                            <p class="text-xs font-semibold text-slate-700 mb-2">Aset yang sedang dipinjam:</p>
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
                        <div x-show="karyawanItems.length === 0" class="mt-3 pt-3 border-t border-emerald-100 mb-3">
                            <p class="text-xs text-slate-500 italic">Belum ada aset yang dipinjam.</p>
                        </div>

                        <!-- Button Tambah Pinjaman -->
                        <button type="button" x-show="!showInputForm" @click="showInputForm = true" class="w-full py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-bold text-xs rounded-xl transition-colors flex items-center justify-center">
                            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Tambah Pinjaman
                        </button>
                    </div>

                    <!-- Hasil: Tidak ditemukan -->
                    <div x-show="!karyawanFound && sudahCari" class="mb-4 border border-rose-100 bg-rose-50/30 rounded-xl p-4 text-xs text-rose-700" style="display:none;">
                        <i data-lucide="user-x" class="w-4 h-4 inline mr-1"></i> Karyawan tidak ditemukan. <a href="{{ route('karyawan') }}" class="underline">Tambah data karyawan</a>.
                    </div>

                    <!-- Item Aset (tampil setelah karyawan ditemukan dan klik Tambah) -->
                    <div x-show="karyawanFound && showInputForm" style="display:none;" class="animate-fade-in border-t border-slate-100 pt-4 mt-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-3" x-text="borrowType === 'dalam' ? 'Input Aset Peminjaman' : 'Pilih Aset untuk Peminjaman Luar'"></label>
                        
                        <!-- Peminjaman Internal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="pm-items" x-show="borrowType === 'dalam'">
                            @php
                                $pmKategori = [
                                    'laptop'       => 'Laptop',
                                    'charger'      => 'Charger',
                                    'mouse'        => 'Mouse',
                                    'lan_extender' => 'LAN Extender',
                                    'headset'      => 'Headset',
                                    'hp_root'      => 'HP Root',
                                    'audio_jack'   => 'Audio Jack',
                                ];
                            @endphp
                            @foreach($pmKategori as $key => $label)
                            <div class="flex flex-col">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">{{ $label }}</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="items[{{ $key }}][no_aset]"
                                        placeholder="Input No. Aset {{ $label }}..."
                                        class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-all">
                                    <!-- Hidden foto input, dipicu kamera -->
                                    <input type="file" name="items[{{ $key }}][foto]" accept="image/*" capture="environment"
                                        class="hidden" id="cam-pm-{{ $key }}">
                                    <button type="button" onclick="document.getElementById('cam-pm-{{ $key }}').click()"
                                        title="Foto No. Aset"
                                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm border border-slate-200">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Peminjaman Eksternal (Luar) -->
                        <div x-show="borrowType === 'luar'" class="space-y-4 border border-slate-200 bg-slate-50 p-4 rounded-xl">
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
                                    Karyawan ini belum meminjam aset apapun. Tidak ada aset yang bisa dibawa keluar.
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-200">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama SPV (Atasan) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="spv_name" placeholder="Ketik nama SPV..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]" :required="borrowType === 'luar'">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama HRD <span class="text-rose-500">*</span></label>
                                    <input type="text" name="hrd_name" placeholder="Ketik nama HRD..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]" :required="borrowType === 'luar'">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4" x-show="karyawanFound && showInputForm" style="display:none;">
                        <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] transition-colors text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm">
                            Simpan Peminjaman
                        </button>
                    </div>
                    </form>
                </div>

                <!-- FORM: Penukaran -->
                <form x-show="activeTab === 'penukaran'" x-data="penukaranApp()"
                    action="{{ route('transaksi.penukaran') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    <input type="hidden" name="karyawan_id" x-model="karyawanId">

                    <!-- Pencarian Karyawan -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Karyawan</label>
                        <div class="flex space-x-2">
                            <input type="text" x-model="cariNama" @keyup.enter.prevent="cariKaryawan()" @keydown.enter.prevent placeholder="Cari nama karyawan..." class="flex-1 px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                            <button type="button" @click="cariKaryawan()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium transition-colors">Cari</button>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Jika belum ada, tambahkan dulu di menu Karyawan.</p>
                    </div>

                    <!-- Hasil Karyawan -->
                    <div x-show="karyawanFound" class="mb-4 border border-emerald-100 bg-emerald-50/30 rounded-xl p-4" style="display:none;">
                        <h4 class="text-sm font-bold text-slate-800 mb-1 flex items-center">
                            <i data-lucide="user-check" class="w-4 h-4 mr-2 text-emerald-600"></i>
                            Karyawan: <span class="ml-1 font-mono text-emerald-700" x-text="karyawanNama"></span>
                        </h4>
                        <p class="text-xs text-slate-500">Department: <span x-text="karyawanDept"></span></p>
                    </div>
                    <div x-show="!karyawanFound && sudahCari" class="mb-4 border border-rose-100 bg-rose-50/30 rounded-xl p-4 text-xs text-rose-700" style="display:none;">
                        <i data-lucide="user-x" class="w-4 h-4 inline mr-1"></i> Karyawan tidak ditemukan. <a href="{{ route('karyawan') }}" class="underline">Tambah data karyawan</a>.
                    </div>

                    <!-- Pilihan Penukaran Karyawan -->
                    <div x-show="karyawanFound" style="display:none;" class="mt-4 border-t border-slate-100 pt-4">
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Aset yang Akan Ditukar:</label>
                            
                            <template x-if="karyawanItems.length === 0">
                                <div class="text-xs text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-100">
                                    <i data-lucide="alert-circle" class="w-4 h-4 inline mr-1"></i> Karyawan ini belum memiliki aset aktif.
                                </div>
                            </template>

                            <div class="flex flex-wrap gap-2" x-show="karyawanItems.length > 0">
                                <template x-for="item in karyawanItems" :key="item.id">
                                    <button type="button" @click="toggleSwapCategory(item)" 
                                        class="px-4 py-2 rounded-xl text-xs font-bold border transition-colors flex items-center"
                                        :class="isSwapCategoryActive(getCategoryKey(item)) ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1.5" x-show="isSwapCategoryActive(getCategoryKey(item))"></i>
                                        <span x-text="item.jenis + ' (' + item.sn + ')'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Form Penukaran Dinamis -->
                        <div x-show="activeSwapCategories.length > 0" class="space-y-4">
                            <div class="flex gap-3 text-[11px] font-bold uppercase mb-2">
                                <span class="bg-rose-50 text-rose-500 border border-rose-200 px-2 py-0.5 rounded">Merah = Aset Lama</span>
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded">Hijau = Aset Baru</span>
                            </div>
                            
                            <template x-for="cat in activeSwapCategories" :key="cat.key">
                                <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100 relative">
                                    <button type="button" @click="activeSwapCategories = activeSwapCategories.filter(c => c.key !== cat.key)" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1" x-text="'Penukaran ' + cat.label"></label>
                                    <input type="hidden" :name="'items[' + cat.key + '][kategori]'" :value="cat.label">
                                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                        <div class="flex-1 flex gap-2">
                                            <input type="text" :name="'items[' + cat.key + '][sn_lama]'" :value="cat.sn_lama" readonly
                                                class="w-1/2 px-4 py-2 rounded-xl border border-rose-200 bg-rose-50/50 text-rose-700 text-sm outline-none cursor-not-allowed" title="Aset Lama (Otomatis)">
                                            <input type="text" :name="'items[' + cat.key + '][no_aset]'"
                                                placeholder="No. Aset Baru..." required
                                                class="w-1/2 px-4 py-2 rounded-xl border border-emerald-200 bg-white text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-sm transition-all">
                                        </div>
                                        <div class="relative shrink-0 flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                                            <input type="file" :name="'items[' + cat.key + '][foto]'" accept="image/*" capture="environment"
                                                class="opacity-0 absolute -z-10 w-0 h-0" :id="'cam-pn-' + cat.key" @change="cat.has_foto = true">
                                            <button type="button" @click="document.getElementById('cam-pn-' + cat.key).click()"
                                                :class="cat.has_foto ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-100'"
                                                title="Foto Aset Baru"
                                                class="w-full sm:w-auto px-3 py-2 sm:py-2.5 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm border font-semibold text-xs">
                                                <i data-lucide="camera" class="w-4 h-4 mr-1" x-show="!cat.has_foto"></i>
                                                <i data-lucide="check-circle-2" class="w-4 h-4 mr-1" x-show="cat.has_foto"></i>
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
                    </div>

                    <div class="flex justify-end pt-2" x-show="karyawanFound" style="display:none;">
                        <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm">Proses Penukaran</button>
                    </div>
                </form>


            </div>
        </div>
    </div>

    <!-- TICKET MODAL -->
    <div x-show="showTicketModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" style="display: none;">
        <div @click.away="showTicketModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 flex flex-col overflow-hidden">
            <h3 class="font-bold text-slate-800 mb-2">Tiket Dibuat</h3>
            <p class="text-sm text-slate-600 mb-4" x-text="'Tiket untuk ' + ticketType + ' berhasil dibuat:'"></p>
            <input type="text" :value="ticketLink" readonly class="w-full px-4 py-2 bg-slate-50 rounded-xl text-sm font-mono mb-4 border border-slate-200 outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white">
            <button @click="showTicketModal = false" class="w-full bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">Tutup</button>
        </div>
    </div>

    <!-- DETAIL TRANSACTION MODAL -->
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;"
         x-transition>
        <div @click.away="showDetailModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl flex-shrink-0">
                <div>
                    <h3 class="font-bold text-slate-800">Detail Transaksi</h3>
                    <p class="text-xs text-slate-500 font-mono" x-text="selectedTx ? selectedTx.doc : ''"></p>
                </div>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            
            <div class="p-6 space-y-6 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <template x-if="selectedTx">
                    <div class="space-y-6">
                        <!-- Info Section -->
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <span class="text-xs text-slate-400 block">Tanggal</span>
                                <span class="text-sm font-medium text-slate-700" x-text="selectedTx.tanggal || selectedTx.tgl || '-'"></span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Tipe Transaksi</span>
                                <span class="text-sm font-semibold text-[#1d4ed8]" x-text="
                                    selectedTx.doc.includes('OB') ? 'Serah Terima' : 
                                    (selectedTx.doc.includes('PJ') || selectedTx.doc.includes('KB') || selectedTx.doc.includes('OF') ? 'Peminjaman/Pengembalian' : 'Penukaran')
                                "></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-xs text-slate-400 block">Nama Karyawan</span>
                                <span class="text-sm font-bold text-slate-800" x-text="selectedTx.penerima || selectedTx.peminjam || selectedTx.pengguna || '-'"></span>
                            </div>
                            <template x-if="selectedTx.dept">
                                <div>
                                    <span class="text-xs text-slate-400 block">Departemen</span>
                                    <span class="text-sm font-medium text-slate-700" x-text="selectedTx.dept"></span>
                                </div>
                            </template>
                            <template x-if="selectedTx.status">
                                <div>
                                    <span class="text-xs text-slate-400 block">Status</span>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100" x-text="selectedTx.status"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Items Section -->
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Item Barang</h4>
                            <div class="border border-slate-100 rounded-xl overflow-hidden divide-y divide-slate-100 text-sm">
                                <!-- Serah Terima Items (Mockup list) -->
                                <template x-if="selectedTx.doc.includes('OB')">
                                    <div>
                                        <div class="p-3 flex justify-between bg-slate-50/50">
                                            <span class="font-medium text-slate-700">Laptop Lenovo Thinkpad</span>
                                            <span class="font-mono text-xs text-[#1d4ed8] font-bold">UP-LAP-021</span>
                                        </div>
                                        <div class="p-3 flex justify-between">
                                            <span class="font-medium text-slate-700">Mouse Logitech B100</span>
                                            <span class="font-mono text-xs text-[#1d4ed8] font-bold">UP-MS-104</span>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Peminjaman Items -->
                                <template x-if="selectedTx.doc.includes('PJ') || selectedTx.doc.includes('KB') || selectedTx.doc.includes('OF')">
                                    <div class="p-3 flex justify-between">
                                        <span class="font-medium text-slate-700" x-text="selectedTx.barang"></span>
                                        <span class="font-mono text-xs text-[#1d4ed8] font-bold">UP-AST-982</span>
                                    </div>
                                </template>

                                <!-- Penukaran Items -->
                                <template x-if="selectedTx.doc.includes('EX')">
                                    <div>
                                        <div class="p-3 flex justify-between items-center bg-rose-50/30">
                                            <div>
                                                <span class="font-medium text-slate-700 block text-xs">Barang Rusak (Masuk)</span>
                                                <span class="text-xs text-slate-400">Headset Jabra</span>
                                            </div>
                                            <span class="font-mono text-xs text-rose-600 font-bold" x-text="selectedTx.sn_masuk"></span>
                                        </div>
                                        <div class="p-3 flex justify-between items-center bg-emerald-50/30">
                                            <div>
                                                <span class="font-medium text-slate-700 block text-xs">Barang Baru (Keluar)</span>
                                                <span class="text-xs text-slate-400">Headset Jabra Evolve</span>
                                            </div>
                                            <span class="font-mono text-xs text-emerald-600 font-bold" x-text="selectedTx.sn_keluar"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="showDetailModal = false" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-5 py-2 rounded-xl text-sm font-medium shadow-soft">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    
    <!-- KEMBALIKAN BARANG MODAL -->
    <div x-show="openReturnModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openReturnModal = false" class="bg-white w-full max-w-2xl flex flex-col rounded-2xl shadow-2xl overflow-hidden" style="max-height: 90vh;">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl flex-shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center"><i data-lucide="corner-down-left" class="w-5 h-5 mr-2 text-emerald-500"></i> Proses Pengembalian Barang</h3>
                <button @click="openReturnModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            
            <form action="{{ route('transaksi.pengembalian') }}" method="POST" class="flex flex-col overflow-hidden">
                @csrf
                <input type="hidden" name="transaction_id" :value="selectedTx ? selectedTx.id : ''">
                <input type="hidden" name="peminjam" :value="selectedTx ? selectedTx.peminjam : ''">
                
                <div class="p-6 overflow-y-auto flex-1">
                    <p class="text-sm text-slate-600 mb-6">Silakan verifikasi kondisi barang yang dikembalikan. Apakah ada cacat atau bagian yang kurang?</p>
                    
                    <div class="space-y-3 mb-4">
                        <template x-for="(item, index) in (selectedTx ? selectedTx.items_data : [])" :key="item.no_aset">
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex flex-col sm:flex-row gap-3 sm:items-center">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-text="item.kategori"></span>
                                        <span x-show="item.hak_bawa_pulang" class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-violet-100 text-violet-600 border border-violet-200">
                                            <i data-lucide="home" class="w-2.5 h-2.5 mr-0.5"></i> Bawa Pulang
                                        </span>
                                    </div>
                                    <div class="font-mono text-sm text-[#1d4ed8] font-semibold mt-0.5" x-text="item.no_aset"></div>
                                </div>
                                <div class="shrink-0">
                                    <label class="text-xs text-slate-600 block mb-1">Kondisi</label>
                                    <select :name="'items[' + item.no_aset + '][kondisi]'" class="w-full sm:w-auto px-3 py-1.5 rounded-lg border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white">
                                        <option value="Baik" selected>Baik</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                </div>
                                <div class="shrink-0" x-show="item.hak_bawa_pulang">
                                    <label class="text-xs text-slate-600 block mb-1">Tujuan Retur</label>
                                    <select :name="'items[' + item.no_aset + '][tujuan]'" class="w-full sm:w-auto px-3 py-1.5 rounded-lg border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white">
                                        <option value="kantor">Tetap Pakai (Cabut Hak Bawa Pulang)</option>
                                        <option value="it">Selesai (Kembali ke IT)</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 mt-2">Catatan (Opsional)</label>
                        <textarea name="catatan" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" rows="3" placeholder="Tuliskan catatan teknis jika ada kerusakan..."></textarea>
                    </div>
                </div>
                
                <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end space-x-3 flex-shrink-0">
                    <button type="button" @click="openReturnModal = false" class="px-5 py-2.5 rounded-xl text-slate-600 font-medium hover:bg-slate-200 text-sm transition-colors">Batal</button>
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm transition-colors flex items-center">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Konfirmasi Retur
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- DETAIL MODAL -->
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="showDetailModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl flex-shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <i data-lucide="file-text" class="w-5 h-5 mr-2 text-[#1d4ed8]"></i> Detail Transaksi
                </h3>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Informasi Dokumen</h4>
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">No. Dokumen</p>
                            <p class="font-mono text-sm text-[#1d4ed8] font-medium" x-text="selectedTx?.doc || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Tanggal</p>
                            <p class="text-sm text-slate-700 font-medium" x-text="selectedTx?.tanggal || selectedTx?.tgl || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Pengguna / Penerima</p>
                            <p class="text-sm text-slate-700 font-medium" x-text="selectedTx?.penerima || selectedTx?.peminjam || selectedTx?.pengguna || '-'"></p>
                        </div>
                        <div x-show="selectedTx?.dept">
                            <p class="text-xs text-slate-500 mb-1">Departemen</p>
                            <p class="text-sm text-slate-700 font-medium" x-text="selectedTx?.dept || '-'"></p>
                        </div>
                        <div x-show="selectedTx?.status">
                            <p class="text-xs text-slate-500 mb-1">Status</p>
                            <p class="text-sm text-slate-700 font-medium" x-text="selectedTx?.status || '-'"></p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Daftar Barang</h4>
                    <div class="w-full bg-slate-50 rounded-xl border border-slate-200 overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 border-b border-slate-200 text-slate-600">
                                <tr>
                                    <th class="px-4 py-2 font-medium">Kategori</th>
                                    <th class="px-4 py-2 font-medium">No Aset</th>
                                    <th class="px-4 py-2 font-medium">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                <template x-for="barang in (selectedTx?.items_data || [])" :key="barang.no_aset">
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2 capitalize" x-text="barang.kategori"></td>
                                        <td class="px-4 py-2 font-mono text-[#1d4ed8]" x-text="barang.no_aset"></td>
                                        <td class="px-4 py-2" x-text="barang.keterangan || '-'"></td>
                                    </tr>
                                </template>
                                <tr x-show="!selectedTx?.items_data || selectedTx.items_data.length === 0">
                                    <td colspan="3" class="px-4 py-4 text-center text-slate-500 italic">Tidak ada barang</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="showDetailModal = false" class="px-5 py-2.5 text-slate-500 hover:bg-slate-200 hover:text-slate-700 rounded-xl font-medium transition-colors text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- HISTORY MODAL (KHUSUS PEMINJAMAN) -->
    <div x-show="showHistoryModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="showHistoryModal = false" class="bg-white w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-2xl flex-shrink-0">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <i data-lucide="history" class="w-5 h-5 mr-2 text-[#1d4ed8]"></i> Detail & Riwayat <span class="ml-1" x-text="selectedTx?.peminjam"></span>
                </h3>
                <button @click="showHistoryModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                <!-- 1. Daftar Barang Aktif -->
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center">
                        <i data-lucide="box" class="w-4 h-4 mr-1.5 text-emerald-500"></i> Barang Aktif Saat Ini
                    </h4>
                    <div class="w-full bg-emerald-50/50 rounded-xl border border-emerald-100 overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-emerald-100/50 border-b border-emerald-100 text-emerald-700">
                                <tr>
                                    <th class="px-4 py-2 font-semibold">Kategori</th>
                                    <th class="px-4 py-2 font-semibold">No Aset</th>
                                    <th class="px-4 py-2 font-semibold">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-emerald-50 text-slate-700">
                                <template x-for="barang in (selectedTx?.items_data || [])" :key="barang.no_aset">
                                    <tr class="hover:bg-white transition-colors">
                                        <td class="px-4 py-2 uppercase tracking-wider text-[10px] font-bold text-slate-500" x-text="barang.kategori"></td>
                                        <td class="px-4 py-2 font-mono text-emerald-600 font-semibold" x-text="barang.no_aset"></td>
                                        <td class="px-4 py-2" x-text="barang.keterangan || '-'"></td>
                                    </tr>
                                </template>
                                <tr x-show="!selectedTx?.items_data || selectedTx.items_data.length === 0">
                                    <td colspan="3" class="px-4 py-4 text-center text-slate-500 italic">Tidak ada barang aktif saat ini</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Riwayat Transaksi -->
                <div>
                    <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center">
                        <i data-lucide="file-clock" class="w-4 h-4 mr-1.5 text-blue-500"></i> Riwayat Transaksi
                    </h4>
                    <div class="w-full bg-slate-50 rounded-xl border border-slate-200 overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 border-b border-slate-200 text-slate-600">
                                <tr>
                                    <th class="px-4 py-2 font-semibold">Tgl Transaksi</th>
                                    <th class="px-4 py-2 font-semibold">Dokumen</th>
                                    <th class="px-4 py-2 font-semibold">Jenis</th>
                                    <th class="px-4 py-2 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                <template x-for="hist in (selectedTx?.history || [])" :key="hist.doc">
                                    <tr class="hover:bg-white transition-colors">
                                        <td class="px-4 py-2" x-text="hist.tanggal"></td>
                                        <td class="px-4 py-2 font-mono text-[#1d4ed8] font-medium" x-text="hist.doc"></td>
                                        <td class="px-4 py-2 capitalize font-medium text-slate-600" x-text="hist.type.replace('_', ' ')"></td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider" 
                                                  :class="{
                                                      'bg-amber-100 text-amber-700': hist.status === 'menunggu_validasi',
                                                      'bg-emerald-100 text-emerald-700': hist.status === 'disetujui' || hist.status === 'dikembalikan',
                                                      'bg-rose-100 text-rose-700': hist.status === 'ditolak'
                                                  }" x-text="hist.status"></span>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="!selectedTx?.history || selectedTx.history.length === 0">
                                    <td colspan="4" class="px-4 py-4 text-center text-slate-500 italic">Belum ada riwayat transaksi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end flex-shrink-0">
                <button @click="showHistoryModal = false" class="px-5 py-2.5 text-slate-500 hover:bg-slate-200 hover:text-slate-700 rounded-xl font-medium transition-colors text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- PIN Modal for Delete -->
    <div x-show="openPinModal" 
         class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openPinModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-fade-in text-center p-6">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="lock" class="w-8 h-8 text-rose-500"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Keamanan Penghapusan</h3>
            <p class="text-sm text-slate-500 mb-4">Masukkan 6-digit PIN untuk menghapus <span class="font-bold text-slate-700" x-text="deleteMode === 'bulk' ? selectedIds.length + ' data terpilih' : '1 data'"></span>.</p>
            
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

    
    </div> <!-- END of x-data=transaksiApp -->

    @stack('scripts')
    <script>

        // --- Alpine Data for Forms ---
        function peminjamanApp() {
            return {
                borrowType: 'dalam',
                cariNama: '',
                sudahCari: false,
                karyawanFound: false,
                karyawanNama: '',
                karyawanDept: '',
                karyawanId: '',
                karyawanItems: [],
                showInputForm: false,
                async cariKaryawan() {
                    if (!this.cariNama) return;
                    this.sudahCari = true;
                    try {
                        const response = await fetch(`/workspaceinventory/api/search/karyawan?q=${encodeURIComponent(this.cariNama)}`);
                        const data = await response.json();
                        if (data.found) {
                            this.karyawanFound = true;
                            this.karyawanNama = data.data.nama;
                            this.karyawanDept = data.data.department;
                            this.karyawanId = data.data.id;
                            this.karyawanItems = data.data.items || [];
                            this.showInputForm = false;
                        } else {
                            this.karyawanFound = false;
                            this.karyawanNama = '';
                            this.karyawanDept = '';
                            this.karyawanId = '';
                            this.karyawanItems = [];
                            this.showInputForm = false;
                        }
                    } catch (e) {
                        console.error('Error fetching data', e);
                    }
                }
            }
        }

        function penukaranApp() {
            return {
                cariNama: '',
                sudahCari: false,
                karyawanFound: false,
                karyawanNama: '',
                karyawanNama: '',
                karyawanDept: '',
                karyawanId: '',
                karyawanItems: [],
                activeSwapCategories: [],
                
                getCategoryKey(item) {
                    if (item.sn) return item.sn.replace(/\W/g, '');
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
                        setTimeout(() => window.lucide && window.lucide.createIcons(), 10);
                    }
                },

                isSwapCategoryActive(key) {
                    return this.activeSwapCategories.some(i => i.key === key);
                },

                async cariKaryawan() {
                    if (!this.cariNama) return;
                    this.sudahCari = true;
                    try {
                        const response = await fetch(`/workspaceinventory/api/search/karyawan?q=${encodeURIComponent(this.cariNama)}`);
                        const data = await response.json();
                        if (data.found) {
                            this.karyawanFound = true;
                            this.karyawanNama = data.data.nama;
                            this.karyawanDept = data.data.department;
                            this.karyawanId = data.data.id;
                            this.karyawanItems = data.data.items || [];
                            this.activeSwapCategories = [];
                        } else {
                            this.karyawanFound = false;
                            this.karyawanNama = '';
                            this.karyawanDept = '';
                            this.karyawanId = '';
                            this.karyawanItems = [];
                            this.activeSwapCategories = [];
                        }
                    } catch (e) {
                        console.error('Error fetching data', e);
                    }
                }
            }
        }


        function transaksiApp() {
            return {
                activeTab: localStorage.getItem('tx_activeTab') || 'serah-terima',
                subTipe: 'baru', // baru, existing, pengganti
                subTabPeminjaman: localStorage.getItem('tx_subTabPeminjaman') || 'aktif_dalam',
                showTicketModal: false,
                openModal: false,
                openReturnModal: false,

                showDetailModal: false,
                selectedTx: null,
                showHistoryModal: false,
                subTipe: 'pinjam_internal',
                searchQuery: '',
                filterKategori: 'semua',
                
                searchKaryawan: '',
                karyawanFound: false,
                isKaryawanBaru: false,
                karyawanData: null,
                karyawanItems: [],
                selectedItems: [],
                karyawanDB: [], // No longer used, fetched dynamically
                
                async cariKaryawan() {
                    if(!this.searchKaryawan) return;
                    
                    try {
                        const response = await fetch(`/workspaceinventory/api/search/karyawan?q=${encodeURIComponent(this.searchKaryawan)}`);
                        const result = await response.json();
                        
                        this.karyawanFound = true;
                        if(result.found) {
                            this.isKaryawanBaru = false;
                            this.karyawanData = result.data;
                            this.karyawanItems = result.data.items || [];
                        } else {
                            this.isKaryawanBaru = true;
                            this.karyawanData = { nama: this.searchKaryawan, kontak: '', id_karyawan: '', department: '', posisi: '' };
                            this.karyawanItems = [];
                        }
                    } catch (error) {
                        console.error('Error fetching karyawan:', error);
                    }
                    this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                },
                
                // DATA TRANSAKSI
                serahTerimaList: {!! json_encode($serahTerima->map(fn($t) => [
                    'id' => $t->id,
                    'doc' => $t->doc_number,
                    'tanggal' => $t->created_at->format('d M Y'),
                    'penerima' => $t->nama_pengaju,
                    'dept' => $t->department,
                    'items' => $t->items->count(),
                    'items_data' => $t->items->map($mapItems),
                ])) !!},
                pinjamList: {!! json_encode($activeBorrowers) !!},
                historyPeminjamanList: {!! json_encode(isset($historyPeminjaman) ? $historyPeminjaman->map(fn($t) => [
                    'id' => $t->id,
                    'doc' => $t->doc_number,
                    'peminjam' => $t->nama_pengaju,
                    'dept' => $t->department,
                    'barang' => $t->items->count() . ' Barang',
                    'tgl' => $t->created_at->format('d M Y'),
                    'status' => $t->status,
                    'type' => $t->type,
                    'items_data' => $t->items->map($mapItems),
                ]) : []) !!},
                penukaranList: {!! json_encode($penukaran->map(fn($t) => [
                    'id' => $t->id,
                    'doc' => $t->doc_number,
                    'tanggal' => $t->created_at->format('d M Y'),
                    'pengguna' => $t->nama_pengaju,
                    'sn_masuk' => $t->items->pluck('sn_lama')->filter()->implode(', ') ?: '-',
                    'sn_keluar' => $t->items->pluck('no_aset')->filter()->implode(', ') ?: '-',
                    'items_data' => $t->items->map($mapItems),
                ])) !!},


                getTabName() {
                    if(this.activeTab === 'serah-terima') return 'Serah Terima';
                    if(this.activeTab === 'peminjaman') return 'Peminjaman';
                    if(this.activeTab === 'penukaran') return 'Penukaran';
                    return '';
                },
                selectedIds: [],
                openPinModal: false,
                deleteMode: 'bulk', // 'single' or 'bulk'
                pinInput: '',
                pinError: '',
                deleteProcessing: false,
                deleteItem: null,
                get activeList() {
                    if (this.activeTab === 'serah-terima') return this.serahTerimaList;
                    if (this.activeTab === 'peminjaman') return this.pinjamList;
                    if (this.activeTab === 'penukaran') return this.penukaranList;
                    return [];
                },
                get allSelected() {
                    const list = this.activeList;
                    return list.length > 0 && this.selectedIds.length > 0 && this.selectedIds.length === list.length;
                },
                toggleAll(e) {
                    if (e.target.checked) {
                        this.selectedIds = this.activeList.map(i => i.id);
                    } else {
                        this.selectedIds = [];
                    }
                },
                openDeletePinModal(mode, item = null) {
                    this.deleteMode = mode;
                    if (item) {
                        this.deleteItem = item;
                    }
                    this.openPinModal = true;
                    this.pinInput = '';
                    this.pinError = '';
                },
                processDelete() {
                    this.pinError = '';
                    if (this.pinInput !== '447747') {
                        this.pinError = 'PIN salah!';
                        return;
                    }
                    this.deleteProcessing = true;
                    
                    let idsToDelete = [];
                    if (this.deleteMode === 'single') {
                        idsToDelete = [this.deleteItem.id];
                    } else {
                        idsToDelete = this.selectedIds;
                    }

                    fetch('/workspaceinventory/transaksi/bulk-delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ ids: idsToDelete, pin: this.pinInput })
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            this.pinError = data.message;
                            this.deleteProcessing = false;
                        }
                    }).catch(e => {
                        this.pinError = 'Terjadi kesalahan sistem';
                        this.deleteProcessing = false;
                    });
                },

                init() {
                    this.$watch('activeTab', (val) => {
                        localStorage.setItem('tx_activeTab', val);
                        this.selectedIds = [];
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('subTipe', () => { 
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('searchQuery', () => { 
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('subTabPeminjaman', (val) => { 
                        localStorage.setItem('tx_subTabPeminjaman', val);
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                }
            }
        }
        
        document.addEventListener('alpine:init', () => {
            Alpine.data('transaksiApp', transaksiApp);
        });
    </script>
</x-layout>


