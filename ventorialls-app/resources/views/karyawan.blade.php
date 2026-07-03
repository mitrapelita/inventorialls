<x-layout active="karyawan" headerTitle="Manajemen Karyawan">
    
    <div x-data="karyawanApp()">
        <!-- Top Action Bar -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-700">Data Karyawan</h3>
                <p class="text-slate-500 text-xs">Kelola data karyawan dan departemen yang terkait dengan kepemilikan aset.</p>
            </div>
            <button @click="openModal = true" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-4 py-2 rounded-xl text-sm font-medium shadow-soft shadow-[#1d4ed8]/30 flex items-center transition-all">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Tambah Karyawan
            </button>
        </div>

        <!-- Main Container -->
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 flex flex-col overflow-hidden">
            <!-- Search Bar -->
            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 bg-white">
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari nama atau departemen..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none text-sm">
            </div>
            <div class="flex space-x-2">
                <select class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-xs font-medium outline-none">
                    <option value="">Semua Departemen</option>
                    <option value="IT">IT</option>
                    <option value="Agent">Agent</option>
                    <option value="HR">HR</option>
                </select>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="overflow-x-auto bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3">Nama Lengkap</th>
                            <th class="px-5 py-3">ID Karyawan</th>
                            <th class="px-5 py-3">Departemen & Posisi</th>
                            <th class="px-5 py-3">Nomor Kontak</th>
                            <th class="px-5 py-3">Total Aset</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="item in filteredList" :key="item.id">
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-5 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3 flex-shrink-0 uppercase" x-text="item.nama.substring(0,2)"></div>
                                        <div>
                                            <p class="font-bold text-slate-700 text-xs" x-text="item.nama"></p>
                                            <p class="text-[10px] text-slate-400 font-medium" x-text="item.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-500" x-text="item.id_karyawan"></td>
                                <td class="px-5 py-3">
                                    <p class="text-slate-700 font-medium text-xs" x-text="item.department"></p>
                                    <p class="text-[10px] text-slate-400" x-text="item.posisi"></p>
                                </td>
                                <td class="px-5 py-3 text-slate-600 text-xs font-medium" x-text="item.kontak"></td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center text-xs font-bold text-slate-700">
                                        <i data-lucide="monitor-smartphone" class="w-3.5 h-3.5 mr-1.5 text-slate-400"></i>
                                        <span x-text="item.total_aset"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border tracking-wide uppercase" 
                                        :class="{
                                            'bg-emerald-50 text-emerald-600 border-emerald-200': item.status === 'Aktif',
                                            'bg-rose-50 text-rose-600 border-rose-200': item.status === 'Resign'
                                        }" x-text="item.status"></span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-blue-50 hover:text-[#1d4ed8] hover:border-blue-200 transition-all shadow-sm group" title="Edit Data">
                                            <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                        </button>
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm group" title="Hapus Data">
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
        </div>

        <!-- Modal Form Tambah Karyawan -->
        <div x-show="openModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
             style="display: none;">
            <div @click.away="openModal = false" 
                 class="bg-white rounded-2xl shadow-xl w-full max-w-2xl flex flex-col max-h-[90vh]">
                
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0">
                    <h3 class="text-sm font-bold text-slate-800">Form Tambah Karyawan Baru</h3>
                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="p-5 overflow-y-auto flex-1">
                    <form @submit.prevent="submitData" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" x-model="formData.nama" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">ID Karyawan</label>
                                <input type="text" x-model="formData.id_karyawan" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm font-mono uppercase">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                                <input type="email" x-model="formData.email" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Kontak / WhatsApp</label>
                                <input type="text" x-model="formData.kontak" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Departemen</label>
                                <select x-model="formData.department" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                                    <option value="IT">IT</option>
                                    <option value="Agent">Agent</option>
                                    <option value="HR">HR</option>
                                    <option value="Legal">Legal</option>
                                    <option value="Translator">Translator</option>
                                    <option value="QC">QC</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Posisi / Jabatan</label>
                                <input type="text" x-model="formData.posisi" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat (Opsional)</label>
                                <textarea rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none text-sm"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex justify-end space-x-2 flex-shrink-0">
                    <button @click="openModal = false" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</button>
                    <button @click="submitData" class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] shadow-soft transition-all flex items-center">
                        <i data-lucide="save" class="w-3.5 h-3.5 mr-2"></i>
                        Simpan Data
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
                },
                openModal: false,
                searchQuery: '',
                karyawanList: [
                    { id: 1, nama: 'Gita Novia Ashari', email: 'gita@ventorialls.co', id_karyawan: 'EMP-001', department: 'Agent', posisi: 'Customer Support', kontak: '085727812022', total_aset: 2, status: 'Aktif' },
                    { id: 2, nama: 'Mahfuddin', email: 'mahfuddin@ventorialls.co', id_karyawan: 'EMP-002', department: 'IT', posisi: 'System Administrator', kontak: '082340873094', total_aset: 4, status: 'Aktif' },
                    { id: 3, nama: 'Irfan Bimantoro', email: 'irfan@ventorialls.co', id_karyawan: 'EMP-003', department: 'HR', posisi: 'HR Manager', kontak: '082220030285', total_aset: 1, status: 'Aktif' },
                    { id: 4, nama: 'Sarah Wijaya', email: 'sarah@ventorialls.co', id_karyawan: 'EMP-004', department: 'Legal', posisi: 'Legal Officer', kontak: '08119008007', total_aset: 0, status: 'Resign' }
                ],
                formData: {
                    nama: '', email: '', id_karyawan: '', department: 'IT', posisi: '', kontak: '', status: 'Aktif', total_aset: 0
                },
                get filteredList() {
                    if (this.searchQuery === '') return this.karyawanList;
                    const lowerQuery = this.searchQuery.toLowerCase();
                    return this.karyawanList.filter(k => 
                        k.nama.toLowerCase().includes(lowerQuery) || 
                        k.department.toLowerCase().includes(lowerQuery) ||
                        k.id_karyawan.toLowerCase().includes(lowerQuery)
                    );
                },
                submitData() {
                    if(this.formData.nama && this.formData.id_karyawan) {
                        this.karyawanList.unshift({
                            id: Date.now(),
                            ...this.formData
                        });
                        this.openModal = false;
                        this.formData = {
                            nama: '', email: '', id_karyawan: '', department: 'IT', posisi: '', kontak: '', status: 'Aktif', total_aset: 0
                        };
                        if(window.lucide) {
                            setTimeout(() => { window.lucide.createIcons({ icons: window.lucide.icons }); }, 100);
                        }
                    }
                }
            }
        }
    </script>
    @endpush
</x-layout>

