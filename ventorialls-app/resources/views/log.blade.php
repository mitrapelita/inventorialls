<x-layout active="log" headerTitle="Log Aktivitas Sistem">
    <div x-data="logAktivitasApp()">
        <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 mr-2 md:mr-6">
                <h3 class="text-base font-bold text-slate-700 truncate">Histori Perubahan Data (CRUD)</h3>
                <p class="text-xs text-slate-500 truncate">Mencatat semua aktivitas penambahan, pembaruan, dan penghapusan data di dalam sistem.</p>
            </div>
            
            <div class="flex items-center space-x-2 w-full md:w-auto">
                <div class="relative flex-1 md:w-64">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    <input type="text" x-model="search" placeholder="Cari aktivitas atau entitas..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none transition-all">
                </div>
                <select x-model="filter" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 outline-none text-sm font-medium cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">
                    <option value="semua">Semua Modul</option>
                    <option value="master">Master Data</option>
                    <option value="transaksi">Transaksi</option>
                    <option value="karyawan">Data Karyawan</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3">Waktu</th>
                            <th class="px-5 py-3">User</th>
                            <th class="px-5 py-3">Aksi</th>
                            <th class="px-5 py-3">Modul</th>
                            <th class="px-5 py-3">Detail Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="log in filteredLogs" :key="log.id">
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-5 py-3 font-mono text-xs text-slate-500" x-text="log.waktu"></td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold mr-2 uppercase" x-text="log.user.substring(0,1)"></div>
                                        <span class="font-semibold text-slate-700" x-text="log.user"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border tracking-wide uppercase" 
                                        :class="{
                                            'bg-emerald-50 text-emerald-600 border-emerald-200': log.aksi === 'CREATE',
                                            'bg-blue-50 text-blue-600 border-blue-200': log.aksi === 'UPDATE',
                                            'bg-rose-50 text-rose-600 border-rose-200': log.aksi === 'DELETE',
                                            'bg-amber-50 text-amber-600 border-amber-200': log.aksi === 'EXPORT'
                                        }" x-text="log.aksi"></span>
                                </td>
                                <td class="px-5 py-3 text-slate-500 font-medium text-xs uppercase" x-text="log.modul"></td>
                                <td class="px-5 py-3 text-slate-600 text-xs" x-text="log.detail"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredLogs.length === 0">
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Tidak ada log aktivitas yang cocok dengan pencarian Anda.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Dummy -->
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex justify-between items-center text-xs text-slate-500">
                <span>Menampilkan 1 - 6 dari 45 log</span>
                <div class="flex space-x-1">
                    <button class="px-2 py-1 border border-slate-200 rounded hover:bg-white disabled:opacity-50" disabled>Prev</button>
                    <button class="px-2 py-1 border border-slate-200 rounded bg-white text-[#1d4ed8] font-bold">1</button>
                    <button class="px-2 py-1 border border-slate-200 rounded hover:bg-white">2</button>
                    <button class="px-2 py-1 border border-slate-200 rounded hover:bg-white">3</button>
                    <button class="px-2 py-1 border border-slate-200 rounded hover:bg-white">Next</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function logAktivitasApp() {
            return {
                search: '',
                filter: 'semua',
                logs: [
                    { id: 1, waktu: '2026-07-02 14:30:15', user: 'Admin Inventory', aksi: 'CREATE', modul: 'transaksi', detail: 'Membuat dokumen Serah Terima baru [TRX-OB-002]' },
                    { id: 2, waktu: '2026-07-02 10:15:22', user: 'Super Admin', aksi: 'UPDATE', modul: 'master', detail: 'Memperbarui status No. Aset [UP-LAP-001] menjadi Dipinjam' },
                    { id: 3, waktu: '2026-07-01 16:45:00', user: 'Admin Inventory', aksi: 'CREATE', modul: 'transaksi', detail: 'Membuat dokumen Peminjaman baru [TRX-PJ-001]' },
                    { id: 4, waktu: '2026-07-01 11:20:10', user: 'Super Admin', aksi: 'DELETE', modul: 'karyawan', detail: 'Menghapus data karyawan [Budi Santoso]' },
                    { id: 5, waktu: '2026-06-30 09:10:05', user: 'Admin Inventory', aksi: 'CREATE', modul: 'karyawan', detail: 'Menambahkan data karyawan baru [Gita Novia Ashari]' },
                    { id: 6, waktu: '2026-06-29 15:00:30', user: 'Admin Inventory', aksi: 'EXPORT', modul: 'master', detail: 'Mengunduh laporan Master Data format Excel' }
                ],
                get filteredLogs() {
                    return this.logs.filter(log => {
                        const matchSearch = log.detail.toLowerCase().includes(this.search.toLowerCase()) || 
                                            log.user.toLowerCase().includes(this.search.toLowerCase());
                        const matchFilter = this.filter === 'semua' ? true : log.modul === this.filter;
                        return matchSearch && matchFilter;
                    });
                }
            }
        }
    </script>
    @endpush
</x-layout>
