<x-layout active="histori" headerTitle="Histori Barang">
    <div x-data="historiApp">
        {{-- Form Pencarian --}}
        <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5 border border-slate-100 mb-6">
            <h3 class="text-base font-bold text-slate-700 mb-3">Cek Histori Penggunaan Aset</h3>
            <form method="GET" action="{{ route('histori') }}" class="flex space-x-3">
                <input type="text" name="sn" value="{{ $sn ?? '' }}"
                    placeholder="Masukkan Nomor Aset (SN)..."
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm font-mono focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none">
                <button type="submit" class="px-5 py-2 bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white font-semibold rounded-xl text-sm">
                    Cek Histori
                </button>
            </form>
        </div>

        @if($sn && $inventory)
            {{-- Info Aset --}}
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-4 md:p-5 mb-6">
                <h4 class="text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider">Informasi Aset: {{ $inventory->sn }}</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><span class="text-xs text-slate-400 block">Jenis</span><strong>{{ $inventory->jenis }}</strong></div>
                    <div><span class="text-xs text-slate-400 block">Merk</span><strong>{{ $inventory->merk }}</strong></div>
                    <div><span class="text-xs text-slate-400 block">Kondisi</span><strong>{{ $inventory->kondisi }}</strong></div>
                    <div><span class="text-xs text-slate-400 block">Status</span><strong>{{ $inventory->status }}</strong></div>
                    <div><span class="text-xs text-slate-400 block">Pengguna Saat Ini</span><strong>{{ $inventory->pengguna ?? 'Gudang (Tidak Dipakai)' }}</strong></div>
                    <div><span class="text-xs text-slate-400 block">Department</span><strong>{{ $inventory->department ?? '-' }}</strong></div>
                </div>
            </div>

            {{-- Tabel Histori --}}
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h4 class="text-sm font-bold text-slate-700">Riwayat Transaksi Aset Ini</h4>
                    <button @click="openDeletePinModal('bulk')" :disabled="selectedIds.length === 0" class="px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm flex items-center justify-center transition-all disabled:opacity-50 disabled:cursor-not-allowed" :class="selectedIds.length > 0 ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200' : 'bg-slate-50 text-slate-400 border border-slate-200'">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i>
                        Hapus Terpilih <span x-show="selectedIds.length > 0" x-text="'(' + selectedIds.length + ')'" class="ml-1"></span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                                <th class="px-5 py-3 w-10 text-center">
                                    <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" @change="toggleAll" :checked="allSelected">
                                </th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Tipe Transaksi</th>
                                <th class="px-5 py-3">Karyawan</th>
                                <th class="px-5 py-3">No. Dokumen</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($histori as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                    <td class="px-5 py-3 text-center">
                                        <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer" value="{{ $item->id }}" x-model="selectedIds">
                                    </td>
                                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $item->transaction?->created_at?->format('Y-m-d') ?? '-' }}</td>
                                    <td class="px-5 py-3 font-medium capitalize">{{ str_replace('_', ' ', $item->transaction?->type ?? '-') }}</td>
                                    <td class="px-5 py-3">{{ $item->transaction?->nama_pengaju ?? '-' }}</td>
                                    <td class="px-5 py-3 font-mono text-xs text-[#1d4ed8]">{{ $item->transaction?->doc_number ?? '-' }}</td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border uppercase
                                            {{ $item->transaction?->status === 'disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-50 text-slate-500 border-slate-200' }}">
                                            {{ $item->transaction?->status ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-slate-500">{{ $item->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat transaksi untuk aset ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($sn && !$inventory)
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 md:p-6 text-center">
                <p class="text-rose-600 font-semibold">Aset dengan No. SN <strong class="font-mono">{{ $sn }}</strong> tidak ditemukan di Master Data.</p>
                <a href="{{ route('master') }}" class="mt-3 inline-block text-sm text-[#1d4ed8] hover:underline">→ Tambah aset baru di Master Data</a>
            </div>
        @endif

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
        function historiApp() {
            return {
                selectedIds: [],
                allIds: {!! json_encode(isset($histori) ? collect($histori)->pluck('id')->toArray() : []) !!},
                openPinModal: false,
                deleteMode: 'bulk',
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

                    fetch('/workspaceinventory/histori/bulk-delete', {
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
            Alpine.data('historiApp', historiApp);
        });
    </script>
    @endpush
</x-layout>
