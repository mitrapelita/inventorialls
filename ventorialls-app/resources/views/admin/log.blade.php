<x-layout active="log" headerTitle="Log Aktivitas Sistem">
    <div x-data="logApp">
        <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex-1 min-w-0 mr-2 md:mr-6">
                <h3 class="text-base font-bold text-slate-700 truncate">Histori Perubahan Data (CRUD)</h3>
                <p class="text-xs text-slate-500 truncate">Mencatat semua aktivitas penambahan, pembaruan, penghapusan, dan validasi data di dalam sistem.</p>
            </div>
            <button @click="openDeletePinModal('bulk')" :disabled="selectedIds.length === 0" class="px-4 py-2 rounded-xl text-sm font-medium shadow-soft flex items-center justify-center transition-all disabled:opacity-50 disabled:cursor-not-allowed" :class="selectedIds.length > 0 ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200' : 'bg-slate-50 text-slate-400 border border-slate-200'">
                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                Hapus Terpilih <span x-show="selectedIds.length > 0" x-text="'(' + selectedIds.length + ')'" class="ml-1"></span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="px-5 py-3 w-10 text-center">
                                <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" @change="toggleAll" :checked="allSelected">
                            </th>
                            <th class="px-5 py-3">Waktu</th>
                            <th class="px-5 py-3">Admin</th>
                            <th class="px-5 py-3">Aksi</th>
                            <th class="px-5 py-3">Modul</th>
                            <th class="px-5 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-5 py-3 text-center">
                                    <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" value="{{ $log->id }}" x-model="selectedIds">
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold mr-2 uppercase">
                                            {{ strtoupper(substr($log->admin?->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-700">{{ $log->admin?->name ?? 'Sistem' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $badgeClass = match($log->action) {
                                            'created'  => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'updated'  => 'bg-blue-50 text-blue-600 border-blue-200',
                                            'deleted'  => 'bg-rose-50 text-rose-600 border-rose-200',
                                            'approved' => 'bg-teal-50 text-teal-600 border-teal-200',
                                            'rejected' => 'bg-orange-50 text-orange-600 border-orange-200',
                                            'returned' => 'bg-purple-50 text-purple-600 border-purple-200',
                                            default    => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border tracking-wide uppercase {{ $badgeClass }}">
                                        {{ strtoupper($log->action) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-500 font-medium text-xs uppercase">{{ $log->model_type }}</td>
                                <td class="px-5 py-3 text-slate-600 text-xs">{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada log aktivitas yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex justify-between items-center text-xs text-slate-500">
                <span>Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} log</span>
                <div>{{ $logs->links() }}</div>
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
    </div>

    @push('scripts')
    <script>
        function logApp() {
            return {
                selectedIds: [],
                allIds: {!! json_encode(isset($logs) ? collect($logs->items())->pluck('id')->toArray() : []) !!},
                openPinModal: false,
                deleteMode: 'bulk', // 'single' or 'bulk'
                pinInput: '',
                pinError: '',
                deleteProcessing: false,
                deleteItem: null,
                get allSelected() {
                    return this.selectedIds.length > 0 && this.selectedIds.length === this.allIds.length;
                },
                toggleAll(e) {
                    if (e.target.checked) {
                        this.selectedIds = [...this.allIds];
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

                    fetch('/workspaceinventory/log/bulk-delete', {
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
                }
            }
        }
        document.addEventListener('alpine:init', () => {
            Alpine.data('logApp', logApp);
        });
    </script>
    @endpush
</x-layout>
