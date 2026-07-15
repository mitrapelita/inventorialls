<x-layout active="master" headerTitle="Master Data Inventaris">
    
    <div x-data="inventoryApp()">
        <!-- Alert Notification -->
        <div x-show="showAlert" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-4"
             class="absolute top-4 left-1/2 transform -translate-x-1/2 z-50 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-soft flex items-center w-[500px]"
             style="display: none;">
            <i data-lucide="alert-circle" class="w-6 h-6 mr-3 flex-shrink-0"></i>
            <div class="flex-1">
                <p class="font-bold">Peringatan: Data Duplikat!</p>
                <p class="text-sm">No. Aset atau Barang tersebut sudah pernah didata sebelumnya.</p>
            </div>
            <button @click="showAlert = false" class="text-red-500 hover:text-red-700 focus:outline-none ml-4">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Top Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-700">Daftar Aset Perusahaan</h3>
                <p class="text-slate-500 text-xs">Kelola dan data ulang seluruh inventaris IT.</p>
            </div>
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <button @click="openImportModal = true" class="flex-1 sm:flex-none bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 px-4 py-2 rounded-xl text-sm font-medium flex items-center justify-center transition-all">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2"></i>
                    Import Excel
                </button>
                <button @click="openAddModal()" class="flex-1 sm:flex-none bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-4 py-2 rounded-xl text-sm font-medium shadow-soft shadow-[#1d4ed8]/30 flex items-center justify-center transition-all">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Input Data Baru
                </button>
            </div>
        </div>

        <!-- Main Container -->
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 flex flex-col overflow-hidden">
            
            <!-- Search Bar & Filters -->
            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 bg-white">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-80">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari No. Aset atau Pengguna..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm">
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <select x-model="filterJenis" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 outline-none text-xs font-medium cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">
                    <option value="">Semua Jenis</option>
                    <option value="Laptop">Laptop</option>
                    <option value="LAN Extender">LAN Extender</option>
                    <option value="Charger">Charger</option>
                    <option value="Mouse">Mouse</option>
                    <option value="Headset">Headset</option>
                    <option value="Audio Jack">Audio Jack</option>
                    <option value="HP Root">HP Root</option>
                </select>
                <button @click="openDeletePinModal('bulk')" :disabled="selectedIds.length === 0" class="px-3 py-2 rounded-lg text-xs font-medium transition-all shadow-sm flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" :class="selectedIds.length > 0 ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200' : 'bg-slate-50 text-slate-400 border border-slate-200'">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i>
                    Hapus <span x-show="selectedIds.length > 0" x-text="'(' + selectedIds.length + ')'" class="ml-1"></span>
                </button>
                <button class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 flex items-center text-xs font-medium transition-colors shadow-sm">
                    <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5 text-slate-400"></i>
                    Export
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3 w-10 text-center">
                                <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" @change="toggleAll" :checked="allSelected">
                            </th>
                            <th class="px-5 py-3">Merk / Tipe</th>
                            <th class="px-5 py-3">No. Aset</th>
                            <th class="px-5 py-3">Pengguna</th>
                            <th class="px-5 py-3">Lokasi</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Kondisi</th>
                            <th class="px-5 py-3 text-center">Hak Bawa Pulang</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="item in filteredList" :key="item.sn">
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-5 py-3 text-center">
                                    <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" :value="item.id" x-model="selectedIds">
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 flex-shrink-0"
                                            :class="{
                                                'bg-blue-50 text-blue-600': item.jenis.toLowerCase() === 'laptop',
                                                'bg-amber-50 text-amber-600': item.jenis.toLowerCase() === 'charger',
                                                'bg-emerald-50 text-emerald-600': item.jenis.toLowerCase() === 'mouse',
                                                'bg-indigo-50 text-indigo-600': item.jenis.toLowerCase().includes('lan'),
                                                'bg-rose-50 text-rose-600': item.jenis.toLowerCase() === 'headset',
                                                'bg-purple-50 text-purple-600': ['hp root', 'hp_root', 'smartphone', 'hp'].includes(item.jenis.toLowerCase()),
                                                'bg-orange-50 text-orange-600': ['audio jack', 'audio_jack'].includes(item.jenis.toLowerCase()),
                                                'bg-slate-100 text-slate-600': !['laptop', 'charger', 'mouse', 'lan extender', 'lan_extender', 'headset', 'hp root', 'hp_root', 'smartphone', 'hp', 'audio jack', 'audio_jack'].includes(item.jenis.toLowerCase())
                                            }">
                                            <i data-lucide="laptop" class="w-4 h-4" x-show="item.jenis.toLowerCase() === 'laptop'"></i>
                                            <i data-lucide="battery-charging" class="w-4 h-4" x-show="item.jenis.toLowerCase() === 'charger'"></i>
                                            <i data-lucide="mouse" class="w-4 h-4" x-show="item.jenis.toLowerCase() === 'mouse'"></i>
                                            <i data-lucide="network" class="w-4 h-4" x-show="item.jenis.toLowerCase().includes('lan')"></i>
                                            <i data-lucide="headphones" class="w-4 h-4" x-show="item.jenis.toLowerCase() === 'headset'"></i>
                                            <i data-lucide="smartphone" class="w-4 h-4" x-show="['hp root', 'hp_root', 'smartphone', 'hp'].includes(item.jenis.toLowerCase())"></i>
                                            <i data-lucide="plug" class="w-4 h-4" x-show="['audio jack', 'audio_jack'].includes(item.jenis.toLowerCase())"></i>
                                            <i data-lucide="box" class="w-4 h-4" x-show="!['laptop', 'charger', 'mouse', 'lan extender', 'lan_extender', 'headset', 'hp root', 'hp_root', 'smartphone', 'hp', 'audio jack', 'audio_jack'].includes(item.jenis.toLowerCase())"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700 text-xs" x-text="item.merk"></p>
                                            <p class="text-[10px] text-slate-400 font-medium" x-text="String(item.jenis || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()).replace('Lan ', 'LAN ').replace('Hp ', 'HP ')"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-500" x-text="item.sn"></td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold mr-2 uppercase" x-text="item.pengguna ? item.pengguna.substring(0,1) : '-'"></div>
                                        <div>
                                            <p class="text-slate-700 font-medium text-xs" x-text="item.pengguna || '-'"></p>
                                            <p class="text-[10px] text-slate-400" x-text="item.department"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-600 text-xs font-medium" x-text="item.lokasi"></td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border tracking-wide uppercase" 
                                        :class="{
                                            'bg-emerald-50 text-emerald-600 border-emerald-200': item.status === 'Aktif',
                                            'bg-amber-50 text-amber-600 border-amber-200': item.status === 'Disimpan',
                                            'bg-rose-50 text-rose-600 border-rose-200': item.status === 'Return Vendor'
                                        }" x-text="item.status"></span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="text-slate-600 flex items-center text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2" :class="item.kondisi === 'Baik' ? 'bg-emerald-500' : 'bg-red-500'"></span>
                                        <span x-text="item.kondisi"></span>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span x-show="item.hak_bawa_pulang" class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-violet-50 text-violet-600 border border-violet-200 uppercase tracking-wide">
                                        <i data-lucide="home" class="w-3 h-3 mr-1"></i> Ya
                                    </span>
                                    <span x-show="!item.hak_bawa_pulang" class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-50 text-slate-400 border border-slate-200 uppercase tracking-wide">
                                        Tidak
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button @click="viewData(item)" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm group" title="Lihat Detail">
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
                    </tbody>
                </table>
            </div>
        </div>
        </div>

        <!-- Modal Form Input Data (14 Fields) -->
        <div x-show="openModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
             style="display: none;">
            <div @click.away="openModal = false" 
                 class="bg-white rounded-2xl shadow-xl w-full max-w-4xl flex flex-col h-[95vh] overflow-hidden">
                
                <div class="px-4 py-3 md:px-5 md:py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0 rounded-t-2xl">
                    <h3 class="text-base md:text-lg font-bold text-slate-800" x-text="isEdit ? 'Edit Master Data Inventaris' : 'Lengkapi Master Data Inventaris'"></h3>
                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4 md:w-5 md:h-5"></i>
                    </button>
                </div>
                
                <div class="p-4 md:p-6 overflow-y-auto flex-1">
                    <form :action="formAction" method="POST" class="space-y-6 flex flex-col h-full">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        <div class="flex-1 space-y-6">
                        <!-- Section: Info Barang -->
                        <div>
                            <h4 class="text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-4 border-b pb-2">Informasi Barang</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Barang</label>
                                    <select name="jenis" x-model="formData.jenis" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Laptop">Laptop</option>
                                        <option value="LAN Extender">LAN Extender</option>
                                        <option value="Charger">Charger</option>
                                        <option value="Mouse">Mouse</option>
                                        <option value="Headset">Headset</option>
                                        <option value="HP Root">HP Root</option>
                                        <option value="Audio Jack">Audio Jack</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Merk / Tipe</label>
                                    <input type="text" name="merk" x-model="formData.merk" placeholder="Lenovo Thinkpad" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. Aset</label>
                                    <input type="text" name="sn" x-model="formData.sn" placeholder="UP-LAP-001" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none font-mono">
                                </div>
                                <div x-show="showAdvanced" x-transition>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Masuk</label>
                                    <input type="date" name="tanggal_masuk" x-model="formData.tanggal_masuk" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                </div>
                                <div x-show="showAdvanced" x-transition>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kepemilikan</label>
                                    <select name="kepemilikan" x-model="formData.kepemilikan" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="PTMPTB">PTMPTB</option>
                                        <option value="Vendor">Vendor</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Toggle Advanced Fields -->
                            <div class="mt-4 flex justify-center border-b border-dashed border-slate-200 pb-4">
                                <button type="button" @click="showAdvanced = !showAdvanced" class="px-4 py-2 bg-slate-50 text-[#1d4ed8] hover:bg-blue-50 border border-slate-200 hover:border-[#1d4ed8]/30 rounded-xl text-sm font-semibold transition-all flex items-center shadow-sm">
                                    <span x-text="showAdvanced ? 'Sembunyikan Data Lanjutan' : 'Tampilkan Data Lanjutan'"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 ml-2 transition-transform" :class="showAdvanced ? 'rotate-180' : ''"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Section: Info Pengguna -->
                        <div x-show="showAdvanced" x-transition x-cloak>
                            <h4 class="text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-4 border-b pb-2">Informasi Pengguna</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Pengguna</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="pengguna" x-model="formData.pengguna" placeholder="Nama karyawan" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8]">
                                        <button type="button" @click="searchKaryawan()" class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg border border-blue-200 hover:bg-blue-100 flex-shrink-0" :disabled="isSearching" title="Cari Data Karyawan">
                                            <i data-lucide="search" class="w-4 h-4" x-show="!isSearching"></i>
                                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isSearching" style="display:none"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kontak Pengguna</label>
                                    <input type="text" name="kontak" x-model="formData.kontak" placeholder="0812xxxx" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Department</label>
                                    <select name="department" x-model="formData.department" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Agent">Agent</option>
                                        <option value="IT">IT</option>
                                        <option value="HR">HR</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Translator">Translator</option>
                                        <option value="TL">TL</option>
                                        <option value="QC">QC</option>
                                        <option value="SPV">SPV</option>
                                        <option value="Vendor">Vendor</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Sign In</label>
                                    <input type="date" name="tanggal_signin" x-model="formData.tanggal_signin" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Team Leader (TL)</label>
                                    <input type="text" name="team_leader" x-model="formData.team_leader" placeholder="Nama TL..." class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi</label>
                                    <select name="lokasi" x-model="formData.lokasi" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Di MPTB">Di MPTB (Ruang Kerja)</option>
                                        <option value="Ruangan IT">Ruangan IT</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Status & Kondisi -->
                        <div x-show="showAdvanced" x-transition x-cloak>
                            <h4 class="text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-4 border-b pb-2">Status & Kondisi</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi Barang</label>
                                    <select name="kondisi" x-model="formData.kondisi" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status Ketersediaan</label>
                                    <select name="status" x-model="formData.status" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Disimpan">Disimpan</option>
                                        <option value="Return Vendor">Return Vendor</option>
                                    </select>
                                </div>
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan Tambahan</label>
                                    <textarea name="keterangan" x-model="formData.keterangan" rows="2" placeholder="FAN Rusak, Baterai drop, dll..." class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none"></textarea>
                                </div>
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Hak Bawa Pulang</label>
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="hak_bawa_pulang" value="1" :checked="formData.hak_bawa_pulang" @change="formData.hak_bawa_pulang = true" class="w-4 h-4 accent-violet-600">
                                            <span class="text-sm font-medium text-slate-700">Ya <span class="text-xs text-violet-500">(Boleh dibawa ke luar MPTB)</span></span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="hak_bawa_pulang" value="0" :checked="!formData.hak_bawa_pulang" @change="formData.hak_bawa_pulang = false" class="w-4 h-4 accent-slate-500">
                                            <span class="text-sm font-medium text-slate-700">Tidak</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div> <!-- end content space -->
                        
                        <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3 mt-auto">
                            <button type="button" @click="openModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl font-medium text-white bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] shadow-soft transition-all flex items-center">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan & Validasi'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal View Detail -->
        <div x-show="openViewModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
             style="display: none;">
            <div @click.away="openViewModal = false" 
                 class="bg-white rounded-2xl shadow-xl w-full max-w-3xl flex flex-col overflow-hidden transform transition-all">
                
                <div class="px-4 py-3 md:px-5 md:py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                    <h3 class="text-sm font-bold text-slate-800">Detail & Log Riwayat Inventaris</h3>
                    <button @click="openViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="p-4 md:p-6 overflow-y-auto max-h-[75vh]" x-show="selectedData">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 md:gap-6">
                        
                        <!-- Left: Info Detail (3 cols) -->
                        <div class="md:col-span-3 space-y-4">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 flex-shrink-0"
                                    :class="{
                                        'bg-blue-50 text-blue-600': (selectedData?.jenis || '').toLowerCase() === 'laptop',
                                        'bg-amber-50 text-amber-600': (selectedData?.jenis || '').toLowerCase() === 'charger',
                                        'bg-emerald-50 text-emerald-600': (selectedData?.jenis || '').toLowerCase() === 'mouse',
                                        'bg-indigo-50 text-indigo-600': (selectedData?.jenis || '').toLowerCase().includes('lan'),
                                        'bg-rose-50 text-rose-600': (selectedData?.jenis || '').toLowerCase() === 'headset',
                                        'bg-purple-50 text-purple-600': ['hp root', 'hp_root', 'smartphone', 'hp'].includes((selectedData?.jenis || '').toLowerCase()),
                                        'bg-orange-50 text-orange-600': ['audio jack', 'audio_jack'].includes((selectedData?.jenis || '').toLowerCase()),
                                        'bg-slate-100 text-slate-600': !['laptop', 'charger', 'mouse', 'lan extender', 'lan_extender', 'headset', 'hp root', 'hp_root', 'smartphone', 'hp', 'audio jack', 'audio_jack'].includes((selectedData?.jenis || '').toLowerCase())
                                    }">
                                    <i data-lucide="laptop" class="w-6 h-6" x-show="(selectedData?.jenis || '').toLowerCase() === 'laptop'"></i>
                                    <i data-lucide="battery-charging" class="w-6 h-6" x-show="(selectedData?.jenis || '').toLowerCase() === 'charger'"></i>
                                    <i data-lucide="mouse" class="w-6 h-6" x-show="(selectedData?.jenis || '').toLowerCase() === 'mouse'"></i>
                                    <i data-lucide="network" class="w-6 h-6" x-show="(selectedData?.jenis || '').toLowerCase().includes('lan')"></i>
                                    <i data-lucide="headphones" class="w-6 h-6" x-show="(selectedData?.jenis || '').toLowerCase() === 'headset'"></i>
                                    <i data-lucide="smartphone" class="w-6 h-6" x-show="['hp root', 'hp_root', 'smartphone', 'hp'].includes((selectedData?.jenis || '').toLowerCase())"></i>
                                    <i data-lucide="plug" class="w-6 h-6" x-show="['audio jack', 'audio_jack'].includes((selectedData?.jenis || '').toLowerCase())"></i>
                                    <i data-lucide="box" class="w-6 h-6" x-show="!['laptop', 'charger', 'mouse', 'lan extender', 'lan_extender', 'headset', 'hp root', 'hp_root', 'smartphone', 'hp', 'audio jack', 'audio_jack'].includes((selectedData?.jenis || '').toLowerCase())"></i>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-slate-800" x-text="selectedData?.merk || '-'"></h4>
                                    <p class="font-mono text-slate-500 text-xs" x-text="selectedData?.No. Aset || '-'"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 pt-4 border-t border-slate-100">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Jenis Barang</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="String(selectedData?.jenis || '-').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()).replace('Lan ', 'LAN ').replace('Hp ', 'HP ')"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Kepemilikan</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="selectedData?.kepemilikan || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Pengguna</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="selectedData?.pengguna || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Kontak Pengguna</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="selectedData?.kontak || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Department</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="selectedData?.department || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Team Leader (TL)</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="selectedData?.team_leader || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Tanggal Masuk</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="formatDate(selectedData?.tanggal_masuk)"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Tanggal Sign In</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="formatDate(selectedData?.tanggal_signin)"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Lokasi</p>
                                    <p class="text-xs font-semibold text-slate-700" x-text="selectedData?.lokasi || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Kondisi & Status</p>
                                    <div class="flex space-x-2 mt-0.5">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border tracking-wide uppercase bg-emerald-50 text-emerald-600 border-emerald-200" x-text="selectedData?.kondisi || '-'"></span>
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border tracking-wide uppercase bg-blue-50 text-blue-600 border-blue-200" x-text="selectedData?.status || '-'"></span>
                                    </div>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Keterangan</p>
                                    <p class="text-xs font-medium text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-100" x-text="selectedData?.keterangan || 'Tidak ada keterangan.'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Right: History Log (2 cols) -->
                        <div class="md:col-span-2 border-t md:border-t-0 md:border-l border-slate-100 pt-6 md:pt-0 md:pl-6">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center">
                                <i data-lucide="history" class="w-4 h-4 mr-2 text-[#1d4ed8]"></i>
                                Log Riwayat Pengguna
                            </h4>
                            <div class="relative border-l-2 border-slate-100 ml-2 pl-4 space-y-6 py-2">
                                <!-- Step 1 (Current) -->
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1 bg-white p-0.5">
                                        <div class="w-2.5 h-2.5 bg-blue-600 rounded-full border border-white"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wide block" x-text="formatDate(selectedData?.tanggal_signin) || '02 Juli 2026'"></span>
                                    <h5 class="text-xs font-semibold text-slate-700" x-text="selectedData?.pengguna || 'Tidak ada pengguna'"></h5>
                                    <p class="text-[10px] text-slate-400 mt-0.5" x-text="'Serah Terima (' + (selectedData?.department || 'Agent') + ')'"></p>
                                </div>
                                <!-- Step 2 (Previous) -->
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1 bg-white p-0.5">
                                        <div class="w-2.5 h-2.5 bg-slate-300 rounded-full border border-white"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block">24 Jun 2026</span>
                                    <h5 class="text-xs font-semibold text-slate-700">Rina</h5>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Peminjaman (Bawa Pulang)</p>
                                </div>
                                <!-- Step 3 (Initial) -->
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1 bg-white p-0.5">
                                        <div class="w-2.5 h-2.5 bg-slate-300 rounded-full border border-white"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block" x-text="formatDate(selectedData?.tanggal_masuk) || '20 Juni 2026'"></span>
                                    <h5 class="text-xs font-semibold text-slate-700">Registrasi Awal</h5>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Barang Masuk Gudang IT</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex justify-end">
                    <button @click="openViewModal = false" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Tutup</button>
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

    <!-- Import Excel Modal -->
    <div x-show="openImportModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
         style="display: none;">
        <div @click.away="openImportModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800">Import Data dari Excel</h3>
                <button @click="openImportModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <div class="p-5">
                <div class="bg-emerald-50 text-emerald-800 text-xs rounded-xl p-4 mb-4 border border-emerald-100 flex items-start">
                    <i data-lucide="info" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold mb-1">Format Excel yang didukung (.xlsx, .xls)</p>
                        <p>Pastikan baris pertama berisi *Header* kolom persis dengan ejaan berikut:</p>
                        <ul class="list-disc ml-4 mt-1 space-y-0.5 font-medium">
                            <li>Jenis Barang</li>
                            <li>Merk/Tipe</li>
                            <li>No. Aset</li>
                        </ul>
                    </div>
                </div>

                <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center text-center transition-colors mb-4 relative" :class="excelFile ? 'bg-emerald-50 border-emerald-300' : 'hover:bg-slate-50 hover:border-[#1d4ed8]'">
                    <input type="file" accept=".xlsx, .xls, .csv" @change="handleFileUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" :disabled="importProcessing">
                    <div x-show="!excelFile">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-500">
                            <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm font-bold text-[#1d4ed8] mb-1">Klik atau drag file Excel kesini</p>
                        <p class="text-xs text-slate-500">Maksimal 5MB</p>
                    </div>
                    <div x-show="excelFile">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3 text-emerald-600">
                            <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm font-bold text-emerald-700 mb-1" x-text="excelFile?.name"></p>
                        <p class="text-xs text-emerald-600" x-text="importPreviewCount + ' baris data ditemukan'"></p>
                    </div>
                </div>

                <div x-show="importError" class="bg-rose-50 text-rose-600 text-xs p-3 rounded-lg mb-4 border border-rose-100" x-text="importError"></div>
                <div x-show="importSuccess" class="bg-emerald-50 text-emerald-600 text-xs p-3 rounded-lg mb-4 border border-emerald-100" x-text="importSuccess"></div>
                
                <div x-show="skippedItems.length > 0" class="bg-amber-50 rounded-xl p-4 mb-4 border border-amber-100 max-h-48 overflow-y-auto">
                    <p class="text-xs font-bold text-amber-700 mb-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                        Peringatan: <span x-text="skippedItems.length"></span> Data Duplikat Dilewati
                    </p>
                    <ul class="text-xs text-amber-600 list-disc ml-4 space-y-1">
                        <template x-for="item in skippedItems" :key="item.sn">
                            <li><span class="font-bold" x-text="item.sn"></span> - <span x-text="item.merk"></span> (<span class="italic" x-text="item.reason"></span>)</li>
                        </template>
                    </ul>
                </div>

                <div class="flex space-x-3 justify-end mt-4">
                    <button type="button" @click="closeImportModal" class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        <span x-text="importSuccess ? 'Tutup' : 'Batal'"></span>
                    </button>
                    <button type="button" @click="processImport()" x-show="!importSuccess" :disabled="!excelFile || importProcessing" class="px-6 py-2 rounded-xl text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-soft disabled:opacity-50 flex items-center justify-center min-w-[120px]">
                        <span x-show="!importProcessing">Import Data</span>
                        <div x-show="importProcessing" class="flex items-center space-x-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                            <span>Memproses...</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    @push('scripts')
    <!-- CDN for SheetJS to parse Excel files in browser -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        function inventoryApp() {
            return {
                init() {
                    this.$watch('filterJenis', () => { 
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('searchQuery', () => { 
                        this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                    });
                    this.$watch('filteredList', () => { this.selectedIds = []; });
                },
                formatDate(dateString) {
                    if (!dateString || dateString === '-') return '-';
                    const d = new Date(dateString);
                    if (isNaN(d.getTime())) return dateString;
                    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
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

                    fetch('/workspaceinventory/master/bulk-delete', {
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
                openViewModal: false,
                openDeleteModal: false,
                openImportModal: false,
                excelFile: null,
                importError: '',
                importSuccess: '',
                importProcessing: false,
                importPreviewCount: 0,
                importDataPayload: [],
                skippedItems: [],
                
                closeImportModal() {
                    this.openImportModal = false;
                    if (this.importSuccess) {
                        window.location.reload();
                    }
                },

                handleFileUpload(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.excelFile = file;
                    this.importError = '';
                    this.importSuccess = '';
                    this.importDataPayload = [];
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        try {
                            const data = new Uint8Array(e.target.result);
                            const workbook = XLSX.read(data, {type: 'array'});
                            const firstSheetName = workbook.SheetNames[0];
                            const worksheet = workbook.Sheets[firstSheetName];
                            const json = XLSX.utils.sheet_to_json(worksheet, {header: 1, defval: ''});
                            
                            if (json.length < 2) {
                                this.importError = 'File Excel kosong atau tidak memiliki baris data.';
                                return;
                            }
                            
                            const headers = json[0].map(h => String(h).trim().toLowerCase());
                            const idxJenis = headers.findIndex(h => h === 'jenis barang');
                            const idxMerk = headers.findIndex(h => h === 'merk/tipe' || h === 'merk / tipe');
                            const idxSn = headers.findIndex(h => h === 'no. aset' || h === 'no aset');
                            
                            if (idxJenis === -1 || idxMerk === -1 || idxSn === -1) {
                                this.importError = 'Format kolom tidak sesuai. Pastikan ada kolom "Jenis Barang", "Merk/Tipe", dan "No. Aset".';
                                return;
                            }
                            
                            const payload = [];
                            for (let i = 1; i < json.length; i++) {
                                const row = json[i];
                                const jenis = String(row[idxJenis] || '').trim();
                                const merk = String(row[idxMerk] || '').trim();
                                const sn = String(row[idxSn] || '').trim();
                                
                                if (jenis && merk && sn) {
                                    payload.push({ jenis, merk, sn });
                                }
                            }
                            
                            if (payload.length === 0) {
                                this.importError = 'Tidak ada baris data yang valid ditemukan.';
                                return;
                            }
                            
                            this.importPreviewCount = payload.length;
                            this.importDataPayload = payload;
                        } catch (err) {
                            console.error(err);
                            this.importError = 'Gagal membaca file Excel. Pastikan formatnya benar.';
                        }
                    };
                    reader.readAsArrayBuffer(file);
                },
                
                processImport() {
                    if (this.importDataPayload.length === 0) {
                        this.importError = 'Tidak ada data valid untuk diimpor.';
                        return;
                    }
                    this.importProcessing = true;
                    this.importError = '';
                    
                    fetch('{{ route("inventory.import") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.importDataPayload)
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            this.importSuccess = res.message;
                            if (res.skipped_count && res.skipped_count > 0) {
                                this.skippedItems = res.skipped_items;
                                this.$nextTick(() => { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); });
                            } else {
                                setTimeout(() => window.location.reload(), 1500);
                            }
                            this.importProcessing = false;
                        } else {
                            this.importError = res.message || 'Terjadi kesalahan sistem.';
                            this.importProcessing = false;
                        }
                    })
                    .catch(err => {
                        this.importError = 'Gagal menghubungi server.';
                        this.importProcessing = false;
                    });
                },
                deleteItem: null,
                showAlert: false,
                searchQuery: '',
                filterJenis: '',
                formAction: '/workspaceinventory/master',
                formMethod: 'POST',
                isEdit: false,
                inventoryList: @json($inventories),
                defaultData: {
                    jenis: 'Laptop', merk: '', sn: '', tanggal_masuk: '', kepemilikan: 'PTMPTB',
                    pengguna: '', kontak: '', department: 'Agent', tanggal_signin: '', team_leader: '', lokasi: 'Ruangan IT',
                    hak_bawa_pulang: false,
                    kondisi: 'Baik', status: 'Disimpan', keterangan: ''
                },
                formData: {},
                showAdvanced: false,
                isSearching: false,
                async searchKaryawan() {
                    if (!this.formData.pengguna) return;
                    this.isSearching = true;
                    try {
                        const response = await fetch(`/workspaceinventory/api/search/karyawan?q=${encodeURIComponent(this.formData.pengguna)}`);
                        const data = await response.json();
                        if (data.found && data.data) {
                            this.formData.pengguna = data.data.nama;
                            this.formData.department = data.data.department;
                            this.formData.kontak = data.data.kontak;
                            this.formData.status = 'Aktif';
                        } else {
                            alert("Karyawan tidak ditemukan!");
                        }
                    } catch (e) {
                        console.error('Error fetching data', e);
                    } finally {
                        this.isSearching = false;
                    }
                },
                get filteredList() {
                    let list = this.inventoryList;
                    
                    if (this.filterJenis !== '') {
                        const filterVal = this.filterJenis.toLowerCase().replace(/_/g, ' ');
                        list = list.filter(i => (i.jenis || '').toLowerCase().replace(/_/g, ' ') === filterVal);
                    }

                    if (this.searchQuery !== '') {
                        const lowerQuery = this.searchQuery.toLowerCase();
                        list = list.filter(i => 
                            (i.sn && i.sn.toLowerCase().includes(lowerQuery)) || 
                            (i.pengguna && i.pengguna.toLowerCase().includes(lowerQuery)) ||
                            (i.department && i.department.toLowerCase().includes(lowerQuery))
                        );
                    }
                    
                    return list;
                },
                viewData(item) {
                    this.selectedData = item;
                    this.openViewModal = true;
                },
                openAddModal() {
                    this.isEdit = false;
                    this.showAdvanced = false;
                    this.formAction = '/workspaceinventory/master';
                    this.formMethod = 'POST';
                    this.formData = JSON.parse(JSON.stringify(this.defaultData));
                    this.openModal = true;
                },
                editData(item) {
                    this.isEdit = true;
                    this.showAdvanced = true;
                    this.formAction = '/workspaceinventory/master/' + item.id;
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
            Alpine.data('inventoryApp', () => {
                const app = inventoryApp();
                app.initData();
                return app;
            });
        });
    </script>
    @endpush
</x-layout>



