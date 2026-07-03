<x-layout active="transaksi" headerTitle="Manajemen Transaksi">
    <div x-data="transaksiApp()" class="flex flex-col h-full">
      
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
            <button @click="activeTab = 'pinjam-kembali'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'pinjam-kembali' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'pinjam-kembali' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-600 group-hover:bg-amber-200'">
                    <i data-lucide="repeat" class="w-4 h-4"></i>
                </div>
                Pinjam & Kembali
            </button>
            <button @click="activeTab = 'penukaran'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'penukaran' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'penukaran' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200'">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </div>
                Penukaran
            </button>
          </div>

          <!-- Create Button -->
          <button @click="openModal = true" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-5 py-2.5 rounded-xl font-medium shadow-soft shadow-[#1d4ed8]/30 transition-all flex items-center text-sm shrink-0">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            <span x-text="'Buat ' + getTabName()"></span>
          </button>
      </div>

      <!-- Main Container -->
      <div class="bg-white rounded-2xl shadow-soft border border-slate-100 flex-1 overflow-hidden flex flex-col">
          
          <!-- Search Bar & Filters -->
          <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 bg-white">
              <div class="relative w-full sm:w-80">
              <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
              <input type="text" x-model="searchQuery" placeholder="Cari Dokumen atau Nama..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm">
          </div>
          <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
              <!-- Filter Tanggal (Khusus Serah Terima) -->
              <input x-show="activeTab === 'serah-terima'" type="date" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 outline-none text-xs font-medium cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">

              <!-- Filter Kategori Dinamis -->
              <select x-show="activeTab !== 'serah-terima'" x-model="filterKategori" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 outline-none text-xs font-medium cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">
                  <option value="semua">Semua Kategori</option>
                  
                  <!-- Opsi Filter untuk Pinjam & Kembali -->
                  <template x-if="activeTab === 'pinjam-kembali'">
                      <option value="dipinjam">Status: Sedang Dipinjam</option>
                  </template>
                  <template x-if="activeTab === 'pinjam-kembali'">
                      <option value="dikembalikan">Status: Sudah Dikembalikan</option>
                  </template>
                  <template x-if="activeTab === 'pinjam-kembali'">
                      <option value="offboarding">Status: Offboarding</option>
                  </template>
              </select>
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
                        <th class="px-5 py-3 font-semibold">No. Dokumen</th>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Penerima</th>
                        <th class="px-5 py-3 font-semibold">Department</th>
                        <th class="px-5 py-3 font-semibold">Total Item</th>
                        <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="item in serahTerimaList" :key="item.doc">
                        <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                            <td class="px-5 py-3 font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                            <td class="px-5 py-3" x-text="item.tanggal"></td>
                            <td class="px-5 py-3 font-medium text-slate-700" x-text="item.penerima"></td>
                            <td class="px-5 py-3" x-text="item.dept"></td>
                            <td class="px-5 py-3">
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full text-xs font-medium" x-text="item.items + ' Barang'"></span>
                            </td>
                            <td class="px-5 py-3 text-center flex items-center justify-center space-x-2">
                                <button @click="selectedTx = item; showDetailModal = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button @click="showBast = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Cetak BAST">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- 2. Pinjam & Kembali Table -->
        <div x-show="activeTab === 'pinjam-kembali'" class="flex-1 overflow-auto" style="display: none;">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
                        <th class="px-5 py-3 font-semibold">No. Dokumen</th>
                        <th class="px-5 py-3 font-semibold">Peminjam</th>
                        <th class="px-5 py-3 font-semibold">Barang</th>
                        <th class="px-5 py-3 font-semibold">Tgl Pinjam</th>
                        <th class="px-5 py-3 font-semibold">Tipe / Status</th>
                        <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                        <th class="px-5 py-3 font-semibold text-center">Pengembalian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="item in pinjamList" :key="item.doc">
                        <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                            <td class="px-5 py-3 font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                            <td class="px-5 py-3 font-medium text-slate-700" x-text="item.peminjam"></td>
                            <td class="px-5 py-3" x-text="item.barang"></td>
                            <td class="px-5 py-3" x-text="item.tgl"></td>
                            <td class="px-5 py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold" 
                                      :class="{
                                          'bg-amber-50 text-amber-600 border border-amber-200': item.status === 'Dipinjam',
                                          'bg-emerald-50 text-emerald-600 border border-emerald-200': item.status === 'Dikembalikan',
                                          'bg-rose-50 text-rose-600 border border-rose-200': item.status === 'Offboarding'
                                      }" x-text="item.status"></span>
                            </td>
                            <td class="px-5 py-3 text-center flex items-center justify-center space-x-2">
                                <button @click="selectedTx = item; showDetailModal = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button @click="showBast = true" class="text-slate-400 hover:text-[#1d4ed8] transition-colors p-1.5 rounded-lg hover:bg-blue-50" title="Cetak BAST">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </button>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <template x-if="item.status === 'Dipinjam'">
                                    <button @click="openReturnModal = true" class="inline-flex items-center text-emerald-600 hover:bg-emerald-50 rounded-lg px-2.5 py-1 transition-colors text-xs font-bold border border-emerald-200 bg-white shadow-sm" title="Kembalikan Barang">
                                        <i data-lucide="corner-down-left" class="w-3.5 h-3.5 mr-1"></i> Retur
                                    </button>
                                </template>
                                <template x-if="item.status !== 'Dipinjam'">
                                    <span class="text-xs text-slate-400">-</span>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- 3. Penukaran Table -->
        <div x-show="activeTab === 'penukaran'" class="flex-1 overflow-auto" style="display: none;">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-100">
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
                            <td class="px-5 py-3 font-mono text-[#1d4ed8]" x-text="item.doc"></td>
                            <td class="px-5 py-3" x-text="item.tanggal"></td>
                            <td class="px-5 py-3 font-medium text-slate-700" x-text="item.pengguna"></td>
                            <td class="px-5 py-3 font-mono text-xs text-rose-600 bg-rose-50/50" x-text="item.sn_masuk"></td>
                            <td class="px-5 py-3 font-mono text-xs text-emerald-600 bg-emerald-50/50" x-text="item.sn_keluar"></td>
                            <td class="px-5 py-3 text-center flex items-center justify-center">
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
      </div>

    <!-- CREATE TRANSACTION MODAL -->
    <div x-show="openModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openModal = false" class="bg-white w-full max-w-3xl max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800" x-text="'Buat Transaksi: ' + getTabName()"></h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="flex-1 overflow-auto p-6">
                <!-- FORM: Serah Terima -->
                <form x-show="activeTab === 'serah-terima'" @submit.prevent="openModal = false; showBast = true" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" placeholder="Nama lengkap..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">No WA Aktif</label>
                            <input type="text" placeholder="08..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Team Leader</label>
                            <input type="text" placeholder="Team Leader..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">No KTP</label>
                            <input type="text" placeholder="NIK KTP..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Sesuai KTP</label>
                            <input type="text" placeholder="Alamat lengkap KTP..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Domisili Saat Ini</label>
                            <input type="text" placeholder="Alamat domisili..." required class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Ruangan Saat Ini</label>
                            <select class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 outline-none text-sm" required>
                                <option value="">Pilih Ruangan...</option>
                                <option value="Ruang 1">Ruang 1</option>
                                <option value="Ruang 2">Ruang 2</option>
                                <option value="Ruang 3">Ruang 3</option>
                                <option value="Ruang 4">Ruang 4</option>
                                <option value="Ruang 5">Ruang 5</option>
                                <option value="Ruang 6">Ruang 6</option>
                                <option value="Ruang 7">Ruang 7</option>
                                <option value="Ruang 8">Ruang 8</option>
                                <option value="Ruang 9">Ruang 9</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Input No. Aset Berdasarkan Kategori</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Laptop</span>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Input No. Aset Laptop..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                    <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Charger</span>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Input No. Aset Charger..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                    <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Mouse</span>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Input No. Aset Mouse..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                    <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">LAN Extender</span>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Input No. Aset LAN Extender..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                    <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Headset</span>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Input No. Aset Headset..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                    <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">HP Root</span>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Input No. Aset HP Root..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                    <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col md:col-span-2 lg:col-span-1">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Audio Jack</span>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Input No. Aset Audio Jack..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                    <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm">Simpan Transaksi</button>
                    </div>
                </form>

                <!-- FORM: Peminjaman (Khusus Pinjam) -->
                <div x-show="activeTab === 'pinjam-kembali'" class="space-y-6">
                    <div class="flex space-x-4 mb-4 border-b border-slate-100 pb-4">
                        <label class="flex items-center space-x-2 cursor-pointer border border-slate-200 bg-slate-50 px-4 py-2.5 rounded-xl transition-all" :class="subTipe === 'pinjam_internal' ? 'border-[#1d4ed8] bg-blue-50/50' : ''">
                            <input type="radio" x-model="subTipe" value="pinjam_internal" class="text-[#1d4ed8]">
                            <span class="text-sm font-semibold text-slate-700">Internal (Lingkup PTMPTB)</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer border border-slate-200 bg-slate-50 px-4 py-2.5 rounded-xl transition-all" :class="subTipe === 'pinjam_eksternal' ? 'border-indigo-400 bg-indigo-50/50' : ''">
                            <input type="radio" x-model="subTipe" value="pinjam_eksternal" class="text-indigo-600">
                            <span class="text-sm font-semibold text-slate-700">Eksternal (Bawa Pulang)</span>
                        </label>
                    </div>
                    
                    <form @submit.prevent="openModal = false; showBast = true" class="space-y-4">
                        <div class="bg-blue-50/50 p-3 rounded-lg text-xs text-blue-700 mb-4 border border-blue-100 flex items-start" x-show="subTipe === 'pinjam_internal'">
                            <i data-lucide="info" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i> Alat ini hanya akan digunakan di dalam lingkungan kantor PTMPTB.
                        </div>
                        <div class="bg-indigo-50/50 p-3 rounded-lg text-xs text-indigo-700 mb-4 border border-indigo-100 flex items-start" x-show="subTipe === 'pinjam_eksternal'">
                            <i data-lucide="alert-triangle" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i> Peringatan: Alat ini akan dibawa keluar lingkungan PTMPTB (Bawa Pulang). BAST Ekstra akan dicetak.
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Karyawan</label>
                            <div class="flex space-x-2">
                                <input type="text" x-model="searchKaryawan" @keyup.enter="cariKaryawan()" placeholder="Cari nama karyawan untuk load data (WA, Team Leader)..." class="flex-1 px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                                <button type="button" @click="cariKaryawan()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium transition-colors">Cari</button>
                            </div>
                            <p class="text-xs text-slate-500 mt-1"><i data-lucide="info" class="w-3 h-3 inline"></i> Jika data tidak lengkap/belum ada, wajib melengkapi terlebih dahulu.</p>
                        </div>
                        
                        <!-- Karyawan Found State -->
                        <div x-show="karyawanFound" class="mb-6 border border-emerald-100 bg-emerald-50/30 rounded-xl p-4 animate-fade-in" style="display: none;">
                            <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center">
                                <i data-lucide="user-check" class="w-4 h-4 mr-2 text-emerald-600"></i>
                                Data Ditemukan: <span class="ml-1" x-text="karyawanData?.nama"></span>
                            </h4>
                            
                            <!-- Internal: Just list them -->
                            <div x-show="subTipe === 'pinjam_internal'">
                                <p class="text-xs text-slate-600 mb-2 font-semibold">Barang yang sedang digunakan (Internal):</p>
                                <div class="space-y-2 mb-4">
                                    <template x-if="karyawanItems.length === 0"><p class="text-xs text-slate-500 italic">Belum ada barang.</p></template>
                                    <template x-for="item in karyawanItems" :key="item.sn">
                                        <div class="flex justify-between items-center bg-white border border-slate-200 p-2.5 rounded-lg shadow-sm">
                                            <span class="text-xs font-semibold text-slate-700" x-text="item.nama"></span>
                                            <span class="text-xs font-mono text-slate-500 bg-slate-50 px-2 py-1 rounded" x-text="item.sn"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Eksternal: Checkboxes -->
                            <div x-show="subTipe === 'pinjam_eksternal'" style="display: none;">
                                <p class="text-xs text-slate-600 mb-2 font-semibold">Pilih barang yang akan dibawa pulang (Eksternal):</p>
                                <div class="space-y-2 mb-4">
                                    <template x-if="karyawanItems.length === 0"><p class="text-xs text-slate-500 italic">Belum ada barang.</p></template>
                                    <template x-for="item in karyawanItems" :key="item.sn">
                                        <label class="flex justify-between items-center bg-white border border-slate-200 p-2.5 rounded-lg cursor-pointer hover:border-indigo-300 shadow-sm transition-colors">
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" :value="item.sn" x-model="selectedItems" class="text-indigo-600 w-4 h-4 rounded border-slate-300 focus:ring-indigo-500">
                                                <span class="text-xs font-semibold text-slate-700" x-text="item.nama"></span>
                                            </div>
                                            <span class="text-xs font-mono text-slate-500 bg-slate-50 px-2 py-1 rounded" x-text="item.sn"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-2" x-show="karyawanFound" style="display: none;">
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Tambah Pinjaman Baru (Opsional)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Laptop</span>
                                    <div class="flex space-x-2">
                                        <input type="text" placeholder="Input No. Aset Laptop..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                        <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Charger</span>
                                    <div class="flex space-x-2">
                                        <input type="text" placeholder="Input No. Aset Charger..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                        <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Mouse</span>
                                    <div class="flex space-x-2">
                                        <input type="text" placeholder="Input No. Aset Mouse..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                        <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">LAN Extender</span>
                                    <div class="flex space-x-2">
                                        <input type="text" placeholder="Input No. Aset LAN Extender..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                        <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Headset</span>
                                    <div class="flex space-x-2">
                                        <input type="text" placeholder="Input No. Aset Headset..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                        <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">HP Root</span>
                                    <div class="flex space-x-2">
                                        <input type="text" placeholder="Input No. Aset HP Root..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                        <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex flex-col md:col-span-2 lg:col-span-1">
                                    <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Audio Jack</span>
                                    <div class="flex space-x-2">
                                        <input type="text" placeholder="Input No. Aset Audio Jack..." class="w-full px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] focus:bg-white transition-colors">
                                        <button type="button" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                            <i data-lucide="camera" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6" x-show="karyawanFound" style="display: none;">
                            <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] transition-colors text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm">Simpan Peminjaman</button>
                        </div>
                    </form>
                </div>

                <!-- FORM: Penukaran -->
                <form x-show="activeTab === 'penukaran'" @submit.prevent="openModal = false; showBast = true" class="space-y-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Karyawan</label>
                        <input type="text" placeholder="Cari nama karyawan untuk load data (WA, Team Leader)..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                        <p class="text-xs text-slate-500 mt-1"><i data-lucide="info" class="w-3 h-3 inline"></i> Jika data tidak lengkap/belum ada, wajib melengkapi terlebih dahulu.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Input Penukaran Berdasarkan Kategori</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Laptop</span>
                                <div class="flex items-center space-x-1">
                                    <div class="flex-1 flex space-x-1">
                                        <input type="text" placeholder="No. Aset Rusak..." class="w-1/2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-mono text-xs outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <input type="text" placeholder="No. Aset Baru..." class="w-1/2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 font-mono text-xs outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <button type="button" class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Charger</span>
                                <div class="flex items-center space-x-1">
                                    <div class="flex-1 flex space-x-1">
                                        <input type="text" placeholder="No. Aset Rusak..." class="w-1/2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-mono text-xs outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <input type="text" placeholder="No. Aset Baru..." class="w-1/2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 font-mono text-xs outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <button type="button" class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Mouse</span>
                                <div class="flex items-center space-x-1">
                                    <div class="flex-1 flex space-x-1">
                                        <input type="text" placeholder="No. Aset Rusak..." class="w-1/2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-mono text-xs outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <input type="text" placeholder="No. Aset Baru..." class="w-1/2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 font-mono text-xs outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <button type="button" class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">LAN Extender</span>
                                <div class="flex items-center space-x-1">
                                    <div class="flex-1 flex space-x-1">
                                        <input type="text" placeholder="No. Aset Rusak..." class="w-1/2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-mono text-xs outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <input type="text" placeholder="No. Aset Baru..." class="w-1/2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 font-mono text-xs outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <button type="button" class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Headset</span>
                                <div class="flex items-center space-x-1">
                                    <div class="flex-1 flex space-x-1">
                                        <input type="text" placeholder="No. Aset Rusak..." class="w-1/2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-mono text-xs outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <input type="text" placeholder="No. Aset Baru..." class="w-1/2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 font-mono text-xs outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <button type="button" class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">HP Root</span>
                                <div class="flex items-center space-x-1">
                                    <div class="flex-1 flex space-x-1">
                                        <input type="text" placeholder="No. Aset Rusak..." class="w-1/2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-mono text-xs outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <input type="text" placeholder="No. Aset Baru..." class="w-1/2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 font-mono text-xs outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <button type="button" class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col md:col-span-2 lg:col-span-1">
                                <span class="text-[11px] text-slate-500 mb-1 font-bold uppercase tracking-wider">Audio Jack</span>
                                <div class="flex items-center space-x-1">
                                    <div class="flex-1 flex space-x-1">
                                        <input type="text" placeholder="No. Aset Rusak..." class="w-1/2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 font-mono text-xs outline-none focus:ring-2 focus:ring-rose-500/20">
                                        <input type="text" placeholder="No. Aset Baru..." class="w-1/2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 font-mono text-xs outline-none focus:ring-2 focus:ring-emerald-500/20">
                                    </div>
                                    <button type="button" class="px-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0 flex items-center justify-center shadow-sm" title="Foto No. Aset">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm">Proses Penukaran</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- DETAIL TRANSACTION MODAL -->
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;"
         x-transition>
        <div @click.away="showDetailModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
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

    <!-- BAST Modal Print Preview (Existing) -->
    <div x-show="showBast" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
      <div @click.away="showBast = false" class="bg-white w-full max-w-3xl h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
          <h3 class="font-bold text-slate-800">Preview Dokumen Transaksi</h3>
          <button @click="showBast = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        
        <div class="flex-1 overflow-auto p-4 md:p-12 bg-slate-200 flex justify-center">
          <div class="bg-white w-[210mm] min-h-[297mm] max-w-[210mm] shadow-lg p-8 md:p-12 text-slate-800 text-sm md:text-base flex-shrink-0">
            <h1 class="text-center text-lg md:text-xl font-bold uppercase underline mb-8">Berita Acara Transaksi Inventaris</h1>
            <p class="mb-4">Pada hari ini, tanggal <strong>{{ date('d F Y') }}</strong>, telah dilakukan proses transaksi inventaris perusahaan dengan rincian sebagai berikut:</p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div>
                <h4 class="font-bold">Pihak Pertama (Admin):</h4>
                <p>Nama: Admin Inventory</p>
              </div>
              <div>
                <h4 class="font-bold">Pihak Kedua (Karyawan):</h4>
                <p>Nama: Karyawan Terkait</p>
              </div>
            </div>

            <table class="w-full border-collapse border border-slate-800 mb-8">
              <thead>
                <tr class="bg-slate-100">
                  <th class="border border-slate-800 px-3 py-2 text-left">Deskripsi Barang</th>
                  <th class="border border-slate-800 px-3 py-2 text-left">No. Aset</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="border border-slate-800 px-3 py-2">Item Transaksi Terkait</td>
                  <td class="border border-slate-800 px-3 py-2">UP-XXX-000</td>
                </tr>
              </tbody>
            </table>
            
            <p class="mb-8">Para pihak menyatakan telah menyetujui transaksi barang tersebut dalam kondisi sesuai keterangan.</p>

            <div class="flex justify-between mt-12 text-center">
              <div><p class="mb-20">Pihak Pertama,</p><p class="font-bold underline">Admin Inventory</p></div>
              <div><p class="mb-20">Pihak Kedua,</p><p class="font-bold underline">Karyawan Terkait</p></div>
            </div>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-white flex justify-end space-x-4">
          <button @click="showBast = false" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 text-sm font-medium">Tutup</button>
          <button class="px-4 py-2 bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white rounded-lg flex items-center text-sm font-medium">
            <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Dokumen
          </button>
        </div>
      </div>
    </div>
    
    <!-- KEMBALIKAN BARANG MODAL -->
    <div x-show="openReturnModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openReturnModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center"><i data-lucide="corner-down-left" class="w-5 h-5 mr-2 text-emerald-500"></i> Proses Pengembalian Barang</h3>
                <button @click="openReturnModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-6">Silakan verifikasi kondisi barang yang dikembalikan. Apakah ada cacat atau bagian yang kurang?</p>
                <form @submit.prevent="openReturnModal = false; showBast = true" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi Fisik Barang</label>
                        <select class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            <option>Kondisi: Baik & Lengkap</option>
                            <option>Kondisi: Rusak / Cacat Sebagian</option>
                            <option>Kondisi: Hilang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 mt-2">Catatan (Opsional)</label>
                        <textarea class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" rows="3" placeholder="Tuliskan catatan teknis jika ada kerusakan..."></textarea>
                    </div>
                    
                    <div class="flex justify-end pt-4 space-x-3">
                        <button type="button" @click="openReturnModal = false" class="px-5 py-2.5 rounded-xl text-slate-600 font-medium hover:bg-slate-100 text-sm transition-colors">Batal</button>
                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-soft text-sm transition-colors">Konfirmasi Retur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    
    <!-- DETAIL MODAL -->
    <div x-show="showDetailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="showDetailModal = false" class="bg-white w-full max-w-lg flex flex-col rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <i data-lucide="file-text" class="w-5 h-5 mr-2 text-[#1d4ed8]"></i> Detail Transaksi
                </h3>
                <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-slate-700">Informasi Dokumen</h4>
                    <p class="text-xs text-slate-500 mt-1">No: <span class="font-mono text-[#1d4ed8]" x-text="selectedTx?.doc || '-'"></span></p>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Foto Bukti / KTP</h4>
                    <div class="w-full h-40 bg-slate-100 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-slate-300">
                        <i data-lucide="image" class="w-8 h-8 text-slate-400 mb-2"></i>
                        <span class="text-xs text-slate-500">[Dummy Foto KTP / Bukti Barang]</span>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Persetujuan Transaksi</h4>
                    <p class="text-xs text-slate-600 mb-3">Silakan verifikasi data transaksi dan foto bukti sebelum memberikan persetujuan.</p>
                    <div class="flex space-x-3">
                        <button @click="showDetailModal = false" class="flex-1 bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-500 hover:text-white px-4 py-2.5 rounded-xl font-medium transition-colors text-sm flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Setujui
                        </button>
                        <button @click="showDetailModal = false" class="flex-1 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-500 hover:text-white px-4 py-2.5 rounded-xl font-medium transition-colors text-sm flex items-center justify-center">
                            <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Tolak
                        </button>
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
    
    </div> <!-- END of x-data=transaksiApp -->

    @stack('scripts')
    <script>
        function transaksiApp() {
            return {
                activeTab: 'serah-terima',
                openModal: false,
                openReturnModal: false,
                showBast: false,
                showDetailModal: false,
                selectedTx: null,
                subTipe: 'pinjam_internal',
                searchQuery: '',
                filterKategori: 'semua',
                
                searchKaryawan: '',
                karyawanFound: false,
                karyawanData: null,
                karyawanItems: [],
                selectedItems: [],
                karyawanDB: [
                    { nama: 'Mahfuddin', items: [{ nama: 'Laptop Lenovo T14', sn: 'UP-LAP-002' }, { nama: 'Mouse Logitech', sn: 'UP-MOU-005' }] },
                    { nama: 'Irfan Bimantoro', items: [{ nama: 'Headset Jabra', sn: 'UP-HS-012' }] },
                    { nama: 'Gita Novia Ashari', items: [{ nama: 'Laptop HP', sn: 'UP-LAP-015' }] },
                ],
                
                cariKaryawan() {
                    if(!this.searchKaryawan) return;
                    const found = this.karyawanDB.find(k => k.nama.toLowerCase().includes(this.searchKaryawan.toLowerCase()));
                    if(found) {
                        this.karyawanFound = true;
                        this.karyawanData = found;
                        this.karyawanItems = found.items;
                    } else {
                        // Dummy simulate not found but we can add new
                        this.karyawanFound = true;
                        this.karyawanData = { nama: this.searchKaryawan };
                        this.karyawanItems = [];
                    }
                    this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                },
                
                // MOCKUP DATA
                serahTerimaList: [
                    { doc: 'TRX-OB-001', tanggal: '2026-07-01', penerima: 'Gita Novia Ashari', dept: 'Agent', items: 2 },
                    { doc: 'TRX-OB-002', tanggal: '2026-07-02', penerima: 'Mahfuddin', dept: 'IT', items: 3 },
                ],
                pinjamList: [
                    { doc: 'TRX-PJ-001', peminjam: 'Irfan Bimantoro', barang: 'Headset Jabra', tgl: '2026-07-01', status: 'Dipinjam' },
                    { doc: 'TRX-KB-001', peminjam: 'Rina', barang: 'Laptop Lenovo', tgl: '2026-06-25', status: 'Dikembalikan' },
                    { doc: 'TRX-OF-001', peminjam: 'Mahfuddin', barang: 'All Assets', tgl: '2026-06-30', status: 'Offboarding' },
                ],
                penukaranList: [
                    { doc: 'TRX-EX-001', tanggal: '2026-07-02', pengguna: 'Gita Novia', sn_masuk: 'UP-HS-001', sn_keluar: 'UP-HS-005' }
                ],


                getTabName() {
                    if(this.activeTab === 'serah-terima') return 'Serah Terima';
                    if(this.activeTab === 'pinjam-kembali') return 'Peminjaman';
                    if(this.activeTab === 'penukaran') return 'Penukaran';
                    return '';
                },

                init() {
                    this.$watch('activeTab', (val) => {
                        if (val === 'pinjam-kembali' && !this.karyawanFound) {
                            this.searchKaryawan = 'Mahfuddin';
                            this.cariKaryawan();
                        }
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('subTipe', () => { 
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('searchQuery', () => { 
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                }
            }
        }
    </script>
</x-layout>


