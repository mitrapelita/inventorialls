<x-layout active="karyawan" headerTitle="Manajemen Karyawan">
    <div x-data="karyawanApp">
        <!-- Top Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-700">Daftar Karyawan</h3>
                <p class="text-slate-500 text-xs">Kelola data karyawan dan departemen yang terkait dengan kepemilikan aset.</p>
            </div>
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <button @click="openAddModal()" class="flex-1 sm:flex-none bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-4 py-2 rounded-xl text-sm font-medium shadow-soft shadow-[#1d4ed8]/30 flex items-center justify-center transition-all">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Tambah Karyawan
                </button>
            </div>
        </div>

        <!-- Sub-Menu Tabs -->
        <div class="flex space-x-2 border-b border-slate-100 mb-4 mt-2">
            <button @click="subTabKaryawan = 'aktif'" :class="subTabKaryawan === 'aktif' ? 'border-[#1d4ed8] text-[#1d4ed8] border-b-2 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'" class="px-4 py-2 text-sm transition-all focus:outline-none flex items-center">
                <i data-lucide="users" class="w-4 h-4 mr-2"></i> Karyawan Aktif
            </button>
            <button @click="subTabKaryawan = 'riwayat'" :class="subTabKaryawan === 'riwayat' ? 'border-[#1d4ed8] text-[#1d4ed8] border-b-2 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'" class="px-4 py-2 text-sm transition-all focus:outline-none flex items-center">
                <i data-lucide="archive-restore" class="w-4 h-4 mr-2"></i> Riwayat Dihapus
            </button>
        </div>

        <!-- Main Container (Active) -->
        <div x-show="subTabKaryawan === 'aktif'" x-cloak class="bg-white rounded-2xl shadow-soft border border-slate-100 flex flex-col overflow-hidden">
            <!-- Search Bar -->
            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 bg-white">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-80">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama atau departemen..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm">
                </div>
            </div>
            <div class="flex space-x-2">
                <select class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-xs font-medium outline-none">
                    <option value="">Semua Departemen</option>
                    <option value="IT">IT</option>
                    <option value="Agent">Agent</option>
                    <option value="HR">HR</option>
                </select>
                <button @click="openDeletePinModal('bulk')" :disabled="selectedIds.length === 0" class="px-3 py-2 rounded-lg text-xs font-medium transition-all shadow-sm flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" :class="selectedIds.length > 0 ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200' : 'bg-slate-50 text-slate-400 border border-slate-200'">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i>
                    Hapus <span x-show="selectedIds.length > 0" x-text="'(' + selectedIds.length + ')'" class="ml-1"></span>
                </button>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="overflow-x-auto bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3 w-10 text-center">
                                <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" @change="toggleAll" :checked="allSelected">
                            </th>
                            <th class="px-5 py-3">Nama Lengkap</th>
                            <th class="px-5 py-3">ID Karyawan</th>
                            <th class="px-5 py-3">Departemen & Posisi</th>
                            <th class="px-5 py-3">Nomor Kontak</th>
                            <th class="px-5 py-3 text-center">Total Aset</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="item in filteredList" :key="item.id">
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-5 py-3 text-center">
                                    <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" :value="item.id" x-model="selectedIds">
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0 uppercase" x-text="item.name ? item.name.substring(0,2) : '-'"></div>
                                        <div>
                                            <p class="font-bold text-slate-700 text-xs" x-text="item.name"></p>
                                            <p class="text-[10px] text-slate-400 font-medium" x-text="item.email || '-'"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-500" x-text="item.id_karyawan || '-'"></td>
                                <td class="px-5 py-3">
                                    <p class="text-slate-700 font-medium text-xs" x-text="item.department"></p>
                                    <p class="text-[10px] text-slate-400" x-text="item.posisi || '-'"></p>
                                </td>
                                <td class="px-5 py-3 text-slate-600 text-xs font-medium" x-text="item.kontak || '-'"></td>
                                <td class="px-5 py-3 text-center" 
                                    x-data="{ showTooltip: false, mouseX: 0, mouseY: 0 }" 
                                    @mouseenter="showTooltip = true" 
                                    @mouseleave="showTooltip = false" 
                                    @mousemove="mouseX = $event.clientX; mouseY = $event.clientY">
                                    <button @click="viewAssets(item)" class="flex items-center justify-center bg-slate-50 border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold w-16 mx-auto hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-colors shadow-sm cursor-pointer group" title="Lihat Daftar Aset">
                                        <i data-lucide="monitor-smartphone" class="w-3.5 h-3.5 mr-1.5 text-slate-400 group-hover:text-blue-500"></i>
                                        <span x-text="item.total_aset || 0"></span>
                                    </button>
                                    
                                    <!-- Hover Tooltip (Teleported to body to avoid overflow clipping) -->
                                    <template x-teleport="body">
                                        <div x-show="showTooltip && item.total_aset > 0" 
                                             x-cloak 
                                             :style="`left: ${mouseX + 15}px; top: ${mouseY + 15}px;`" 
                                             class="fixed z-[100] bg-white rounded-xl shadow-md border border-slate-100 p-3 min-w-[150px] pointer-events-none transition-opacity duration-150">
                                            <div class="font-bold border-b border-slate-100 pb-2 mb-2 text-slate-800 text-xs" x-text="'Daftar Aset (' + item.total_aset + ')'"></div>
                                            <div class="flex flex-col gap-1.5">
                                                <template x-for="detail in item.daftar_aset" :key="detail.id">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-emerald-500 mr-2 flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                                        <span class="font-mono text-xs text-slate-700 font-semibold" x-text="detail.sn || detail.merk"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border tracking-wide uppercase" 
                                        :class="{
                                            'bg-emerald-50 text-emerald-600 border-emerald-200': item.status_kerja === 'Aktif',
                                            'bg-rose-50 text-rose-600 border-rose-200': item.status_kerja === 'Resign'
                                        }" x-text="item.status_kerja"></span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm group" title="Cetak Profil & Tanggungan Aset" @click="window.open('/workspaceinventory/karyawan/' + item.id + '/print', '_blank')">
                                            <i data-lucide="printer" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                        </button>
                                        <button @click="viewDetail(item)" type="button" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm group" title="Lihat Detail Karyawan">
                                            <i data-lucide="eye" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                        </button>
                                        <button @click="editData(item)" type="button" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-blue-50 hover:text-[#1d4ed8] hover:border-blue-200 transition-all shadow-sm group" title="Edit Data">
                                            <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                        </button>
                                        <button type="button" @click="confirmDelete(item)" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm group" title="Hapus Data">
                                            <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredList.length === 0">
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-xs">
                                Tidak ada data karyawan yang ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Main Container (Riwayat Dihapus) -->
        <div x-show="subTabKaryawan === 'riwayat'" x-cloak class="bg-white rounded-2xl shadow-soft border border-slate-100 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h4 class="text-sm font-bold text-slate-700"><i data-lucide="trash-2" class="w-4 h-4 inline-block mr-1 text-slate-400"></i> Karyawan Terhapus</h4>
                <p class="text-xs text-slate-500">Data dapat dipulihkan dalam 30 hari.</p>
            </div>
            <div class="overflow-x-auto bg-white">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3">Nama Lengkap</th>
                            <th class="px-5 py-3">ID Karyawan</th>
                            <th class="px-5 py-3">Departemen & Posisi</th>
                            <th class="px-5 py-3">Tanggal Dihapus</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($trashedKaryawans as $trashed)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                        {{ substr($trashed->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">{{ $trashed->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $trashed->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $trashed->id_karyawan ?: '-' }}</td>
                            <td class="px-5 py-3">
                                <p class="text-slate-700 font-medium text-xs">{{ $trashed->department }}</p>
                                <p class="text-[10px] text-slate-400">{{ $trashed->posisi ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600 text-xs font-medium">{{ \Carbon\Carbon::parse($trashed->deleted_at)->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-3 text-center">
                                <form action="{{ route('karyawan.restore', $trashed->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="button" onclick="confirmRestore(this.closest('form'))" class="flex items-center justify-center bg-emerald-50 border border-emerald-200 text-emerald-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors shadow-sm" title="Pulihkan Data">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5 mr-1.5"></i>
                                        Pulihkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-xs">
                                Tidak ada data karyawan di riwayat penghapusan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form Tambah Karyawan -->
        <div x-show="openModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
             style="display: none;">
            <div @click.away="openModal = false" 
                 class="bg-white rounded-2xl shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden">
                
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0 rounded-t-2xl">
                    <h3 class="text-sm font-bold text-slate-800" x-text="isEdit ? 'Edit Data Karyawan' : 'Form Tambah Karyawan Baru'"></h3>
                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="p-4 md:p-5 overflow-y-auto flex-1">
                    <form :action="formAction" method="POST" class="space-y-4 flex flex-col h-full">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Basic Karyawan Data -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" x-model="formData.name" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                                <input type="email" name="email" x-model="formData.email" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Kontak / WA Aktif</label>
                                <input type="text" name="kontak" x-model="formData.kontak" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            
                            <!-- Serah Terima / Detail Data -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Team Leader</label>
                                <input type="text" name="nama_tl" x-model="formData.nama_tl" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">No KTP (NIK)</label>
                                <input type="text" name="no_ktp" x-model="formData.no_ktp" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Sesuai KTP</label>
                                <input type="text" name="alamat_ktp" x-model="formData.alamat_ktp" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Domisili Saat Ini</label>
                                <input type="text" name="domisili" x-model="formData.domisili" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Ruangan / Divisi Saat Ini</label>
                                <input type="text" name="department" x-model="formData.department" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Posisi / Jabatan</label>
                                <input type="text" name="posisi" x-model="formData.posisi" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <!-- Hidden status_kerja as Aktif for new -->
                            <input type="hidden" name="status_kerja" value="Aktif">
                        </div>
                        
                        <div class="pt-4 mt-auto flex justify-end space-x-2 flex-shrink-0">
                            <button type="button" @click="openModal = false" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl font-medium text-white bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] shadow-soft transition-all flex items-center">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Data'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Daftar Aset Karyawan -->
        <div x-show="openAssetModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
             style="display: none;"
             x-transition>
            <div @click.away="openAssetModal = false" 
                 class="bg-white rounded-2xl shadow-xl w-full max-w-2xl flex flex-col max-h-[80vh] overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0 rounded-t-2xl">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Daftar Aset Aktif</h3>
                        <p class="text-xs text-slate-500" x-text="selectedKaryawan ? selectedKaryawan.name : ''"></p>
                    </div>
                    <button @click="openAssetModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="p-0 overflow-y-auto flex-1 bg-slate-50/50">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-100/80 border-b border-slate-200 text-[10px] uppercase tracking-wider text-slate-500 sticky top-0">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Jenis Barang</th>
                                <th class="px-5 py-3 font-semibold">Merk / Tipe</th>
                                <th class="px-5 py-3 font-semibold">No. Aset</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            <template x-if="selectedKaryawan && selectedKaryawan.daftar_aset && selectedKaryawan.daftar_aset.length > 0">
                                <template x-for="aset in selectedKaryawan.daftar_aset" :key="aset.id">
                                    <tr class="hover:bg-white transition-colors">
                                        <td class="px-5 py-3 font-semibold text-slate-700" x-text="aset.jenis"></td>
                                        <td class="px-5 py-3 text-slate-600" x-text="aset.merk"></td>
                                        <td class="px-5 py-3 font-mono text-[#1d4ed8]" x-text="aset.sn"></td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="!selectedKaryawan || !selectedKaryawan.daftar_aset || selectedKaryawan.daftar_aset.length === 0">
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-slate-400 italic">Belum ada aset aktif.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                
                <div class="px-5 py-3 border-t border-slate-100 bg-white flex justify-end flex-shrink-0">
                    <button @click="openAssetModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-lg text-sm font-semibold transition-colors">Tutup</button>
                </div>
            </div>
        </div>
        
        <!-- Modal Detail Karyawan -->
        <div x-show="showDetailModal" 
             class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
             style="display: none;">
            <div @click.away="showDetailModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden animate-fade-in">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center">
                        <i data-lucide="user-check" class="w-4 h-4 mr-2 text-indigo-600"></i>
                        Detail Data Karyawan
                    </h3>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="p-4 md:p-6 overflow-y-auto flex-1 bg-white" x-show="selectedKaryawan">
                    <!-- Info Header -->
                    <div class="flex items-start gap-4 mb-6 pb-6 border-b border-slate-100">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl uppercase shrink-0" 
                             x-text="selectedKaryawan?.name?.substring(0, 2)"></div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-bold text-slate-800 mb-1" x-text="selectedKaryawan?.name"></h2>
                            <p class="text-sm text-slate-500 font-mono mb-2" x-text="'ID: ' + (selectedKaryawan?.id_karyawan || '-')"></p>
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-md border tracking-wide uppercase" 
                                  :class="{
                                      'bg-emerald-50 text-emerald-600 border-emerald-200': selectedKaryawan?.status_kerja === 'Aktif',
                                      'bg-rose-50 text-rose-600 border-rose-200': selectedKaryawan?.status_kerja === 'Resign'
                                  }" x-text="selectedKaryawan?.status_kerja"></span>
                        </div>
                    </div>

                    <!-- Grid Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Divisi / Posisi</span>
                            <span class="text-sm font-semibold text-slate-700" x-text="(selectedKaryawan?.department || '-') + ' / ' + (selectedKaryawan?.posisi || '-')"></span>
                        </div>
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Ruangan</span>
                            <span class="text-sm font-semibold text-slate-700" x-text="selectedKaryawan?.ruangan || '-'"></span>
                        </div>
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nomor WA / Kontak</span>
                            <span class="text-sm font-semibold text-slate-700" x-text="selectedKaryawan?.kontak || '-'"></span>
                        </div>
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email</span>
                            <span class="text-sm font-semibold text-slate-700" x-text="selectedKaryawan?.email || '-'"></span>
                        </div>
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nomor KTP (NIK)</span>
                            <span class="text-sm font-semibold text-slate-700 font-mono" x-text="selectedKaryawan?.no_ktp || '-'"></span>
                        </div>
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Team Leader</span>
                            <span class="text-sm font-semibold text-slate-700" x-text="selectedKaryawan?.nama_tl || '-'"></span>
                        </div>
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Alamat KTP</span>
                            <span class="text-sm font-semibold text-slate-700" x-text="selectedKaryawan?.alamat_ktp || '-'"></span>
                        </div>
                        <div class="flex flex-col bg-slate-50 p-3 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Domisili Saat Ini</span>
                            <span class="text-sm font-semibold text-slate-700" x-text="selectedKaryawan?.domisili || '-'"></span>
                        </div>
                    </div>
                </div>
                
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex justify-end flex-shrink-0">
                    <button @click="showDetailModal = false" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 rounded-lg text-sm font-semibold transition-colors shadow-sm">Tutup</button>
                </div>
            </div>
        </div>

    <!-- PIN Modal for Delete -->
    <div x-show="openPinModal" 
         class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openPinModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-fade-in text-center p-4 md:p-6">
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
    </div>
    
    @push('scripts')
    <script>
        function karyawanApp() {
            return {
                init() {
                    this.$watch('searchQuery', () => { 
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('filteredList', () => { this.selectedIds = []; });
                },
                selectedIds: [],
                openPinModal: false,
                deleteMode: 'bulk', // 'single' or 'bulk'
                pinInput: '',
                pinError: '',
                deleteProcessing: false,
                deleteItem: null,
                get allSelected() {
                    return this.selectedIds.length > 0 && this.selectedIds.length === this.filteredList.length;
                },
                toggleAll(e) {
                    if (e.target.checked) {
                        this.selectedIds = this.filteredList.map(i => i.id);
                    } else {
                        this.selectedIds = [];
                    }
                },
                openDeletePinModal(mode) {
                    this.deleteMode = mode;
                    this.openPinModal = true;
                    this.pinInput = '';
                    this.pinError = '';
                },
                processDelete() {
                    this.pinError = '';
                    if (!this.pinInput) {
                        this.pinError = 'PIN harus diisi!';
                        return;
                    }
                    this.deleteProcessing = true;
                    
                    let idsToDelete = [];
                    if (this.deleteMode === 'single') {
                        idsToDelete = [this.deleteItem.id];
                    } else {
                        idsToDelete = this.selectedIds;
                    }

                    fetch('/workspaceinventory/karyawan/bulk-delete', {
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
                openModal: false,
                openDeleteModal: false,
                openAssetModal: false,
                showDetailModal: false,
                subTabKaryawan: 'aktif',
                deleteItem: null,
                selectedKaryawan: null,
                showAlert: false,
                isEdit: false,
                formAction: '/workspaceinventory/karyawan',
                formMethod: 'POST',
                searchQuery: '',
                karyawanList: @json($karyawans),
                defaultData: {
                    name: '', email: '', id_karyawan: '', department: 'IT', posisi: '', kontak: '',
                    nama_tl: '', no_ktp: '', alamat_ktp: '', domisili: '', ruangan: '', status_kerja: 'Aktif'
                },
                formData: {},
                get filteredList() {
                    if (this.searchQuery === '') return this.karyawanList;
                    const lowerQuery = this.searchQuery.toLowerCase();
                    return this.karyawanList.filter(k => 
                        (k.name && k.name.toLowerCase().includes(lowerQuery)) || 
                        (k.department && k.department.toLowerCase().includes(lowerQuery)) ||
                        (k.id_karyawan && k.id_karyawan.toLowerCase().includes(lowerQuery))
                    );
                },
                openAddModal() {
                    this.isEdit = false;
                    this.formAction = '/workspaceinventory/karyawan';
                    this.formMethod = 'POST';
                    this.formData = JSON.parse(JSON.stringify(this.defaultData));
                    this.openModal = true;
                },
                viewAssets(item) {
                    this.selectedKaryawan = item;
                    this.openAssetModal = true;
                },
                viewDetail(item) {
                    this.selectedKaryawan = item;
                    this.showDetailModal = true;
                },
                editData(item) {
                    this.isEdit = true;
                    this.formAction = '/workspaceinventory/karyawan/' + item.id;
                    this.formMethod = 'PUT';
                    this.formData = JSON.parse(JSON.stringify(item));
                    this.openModal = true;
                },
                initData() {
                    this.formData = JSON.parse(JSON.stringify(this.defaultData));
                },
                confirmDelete(item) {
                    this.deleteItem = item;
                    this.openDeletePinModal('single');
                }
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('karyawanApp', () => karyawanApp());
        });
    </script>
    @endpush
    <script>
        function confirmRestore(form) {
            Swal.fire({
                title: 'Pulihkan Karyawan?',
                text: 'Apakah Anda yakin ingin memulihkan karyawan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Pulihkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-layout>
