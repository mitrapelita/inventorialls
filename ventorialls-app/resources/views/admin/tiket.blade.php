<x-layout active="admin-tiket" headerTitle="Manajemen Tiket Karyawan">
    @php
        // Ambil tiket dari database, exclude yang dibatalkan
        $tickets = \App\Models\Ticket::with('creator')
            ->whereIn('status', ['menunggu_diisi','menunggu_validasi','selesai'])
            ->latest()->get();
        $baseUrl = url('');
    @endphp


    <div x-data="tiketApp" class="flex flex-col h-full">
      
        <!-- Top Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tiket Aktif</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola link form yang sudah digenerate untuk diisi oleh Karyawan.</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('transaksi') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-5 py-2.5 rounded-xl font-medium shadow-sm transition-all flex items-center text-sm shrink-0">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    <span>Kembali ke Transaksi</span>
                </a>
                <button @click="openModal = true" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-5 py-2.5 rounded-xl font-medium shadow-soft shadow-[#1d4ed8]/30 transition-all flex items-center text-sm shrink-0">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    <span>Buat Tiket Baru</span>
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex flex-wrap gap-2 mb-6">
            <button @click="activeTab = 'serah-terima'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'serah-terima' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'serah-terima' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600 group-hover:bg-blue-200'">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                </div>
                Serah Terima
            </button>
            <button @click="activeTab = 'peminjaman'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'peminjaman' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'peminjaman' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-600 group-hover:bg-amber-200'">
                    <i data-lucide="repeat" class="w-4 h-4"></i>
                </div>
                Peminjaman
            </button>
            <button @click="activeTab = 'penukaran'" class="flex items-center pr-4 pl-1.5 py-1.5 rounded-xl font-semibold text-sm transition-all shadow-sm border group" :class="activeTab === 'penukaran' ? 'bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white border-[#1d4ed8]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                <div class="p-1.5 rounded-lg mr-2 transition-colors" :class="activeTab === 'penukaran' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200'">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </div>
                Penukaran
            </button>
            <div class="ml-auto flex">
                <button @click="openDeletePinModal('bulk')" :disabled="selectedIds.length === 0" class="px-3 py-1.5 rounded-xl text-xs font-medium shadow-sm flex items-center justify-center transition-all disabled:opacity-50 disabled:cursor-not-allowed" :class="selectedIds.length > 0 ? 'bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200' : 'bg-slate-50 text-slate-400 border border-slate-200'">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i>
                    Hapus Terpilih <span x-show="selectedIds.length > 0" x-text="'(' + selectedIds.length + ')'" class="ml-1"></span>
                </button>
            </div>
        </div>

        {{-- Tiket Grid (Data Real dari DB) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($tickets->where('type', 'serah_terima') as $t)
            <div x-show="activeTab === 'serah-terima'" class="rounded-xl shadow-soft border border-slate-100 p-4 flex flex-col relative overflow-hidden {{ in_array($t->status, ['selesai', 'menunggu_validasi']) ? 'bg-slate-50 opacity-70 grayscale-[50%]' : 'bg-white' }}" style="display: none;">
                <div class="flex justify-between items-start mb-3 relative">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-[#1d4ed8]"><i data-lucide="user-plus" class="w-4 h-4"></i></div>
                    <div class="absolute top-0 right-0">
                        <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer w-4 h-4" value="{{ $t->id }}" x-model="selectedIds">
                    </div>
                    @php $stColor = ['menunggu_diisi'=>'amber','menunggu_validasi'=>'blue','selesai'=>'emerald'][$t->status] ?? 'slate'; $stLabel = ['menunggu_diisi'=>'Menunggu Diisi','menunggu_validasi'=>'Menunggu Validasi','selesai'=>'Selesai'][$t->status] ?? $t->status; @endphp
                    <span class="mr-6 px-2 py-1 bg-{{ $stColor }}-100 text-{{ $stColor }}-700 text-[9px] font-bold uppercase rounded-md border border-{{ $stColor }}-200">{{ $stLabel }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] text-slate-500 font-mono mb-1">{{ $t->ticket_code }}</p>
                    <h3 class="text-sm font-bold text-slate-800 leading-tight mb-1">Serah Terima</h3>
                    <div class="flex items-center text-[10px] text-slate-500"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ $t->created_at->diffForHumans() }}</div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button onclick="copyLink('{{ $baseUrl }}/tiket/serah-terima?id={{ $t->ticket_code }}')"
                        class="flex-1 flex items-center justify-center px-3 py-2 bg-slate-50 text-slate-600 font-semibold rounded-lg text-xs border border-slate-200 hover:bg-slate-100">
                        <i data-lucide="copy" class="w-3 h-3 mr-1.5"></i> Salin Link
                    </button>
                    <form action="{{ route('ticket.destroy', $t->id) }}" method="POST" @submit.prevent="triggerConfirm($event.target)">
                        @csrf @method('DELETE')
                        <button class="flex items-center justify-center px-3 py-2 bg-red-50 text-red-600 font-semibold rounded-lg text-xs border border-red-100 hover:bg-red-100">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div x-show="activeTab === 'serah-terima'" class="col-span-full py-16 text-center text-slate-400 text-sm" style="display:none">
                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-40"></i>
                <p>Belum ada tiket Serah Terima. Buat tiket baru.</p>
            </div>
            @endforelse

            @forelse($tickets->where('type', 'peminjaman') as $t)
            <div x-show="activeTab === 'peminjaman'" class="rounded-xl shadow-soft border border-slate-100 p-4 flex flex-col relative overflow-hidden {{ in_array($t->status, ['selesai', 'menunggu_validasi']) ? 'bg-slate-50 opacity-70 grayscale-[50%]' : 'bg-white' }}" style="display: none;">
                <div class="flex justify-between items-start mb-3 relative">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600"><i data-lucide="repeat" class="w-4 h-4"></i></div>
                    <div class="absolute top-0 right-0">
                        <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer w-4 h-4" value="{{ $t->id }}" x-model="selectedIds">
                    </div>
                    @php $stColor = ['menunggu_diisi'=>'amber','menunggu_validasi'=>'blue','selesai'=>'emerald'][$t->status] ?? 'slate'; $stLabel = ['menunggu_diisi'=>'Menunggu Diisi','menunggu_validasi'=>'Menunggu Validasi','selesai'=>'Selesai'][$t->status] ?? $t->status; @endphp
                    <span class="mr-6 px-2 py-1 bg-{{ $stColor }}-100 text-{{ $stColor }}-700 text-[9px] font-bold uppercase rounded-md border border-{{ $stColor }}-200">{{ $stLabel }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] text-slate-500 font-mono mb-1">{{ $t->ticket_code }}</p>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Peminjaman{{ $t->borrow_type ? ' ('.ucfirst($t->borrow_type).')' : '' }}</h3>
                    <div class="flex items-center text-[10px] text-slate-500"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ $t->created_at->diffForHumans() }}</div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button onclick="copyLink('{{ $baseUrl }}/tiket/peminjaman?id={{ $t->ticket_code }}&tipe={{ $t->borrow_type }}')"
                        class="flex-1 flex items-center justify-center px-3 py-2 bg-slate-50 text-slate-600 font-semibold rounded-lg text-xs border border-slate-200 hover:bg-slate-100">
                        <i data-lucide="copy" class="w-3 h-3 mr-1.5"></i> Salin Link
                    </button>
                    <form action="{{ route('ticket.destroy', $t->id) }}" method="POST" @submit.prevent="triggerConfirm($event.target)">
                        @csrf @method('DELETE')
                        <button class="flex items-center justify-center px-3 py-2 bg-red-50 text-red-600 font-semibold rounded-lg text-xs border border-red-100 hover:bg-red-100">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div x-show="activeTab === 'peminjaman'" class="col-span-full py-16 text-center text-slate-400 text-sm" style="display:none">
                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-40"></i>
                <p>Belum ada tiket Peminjaman. Buat tiket baru.</p>
            </div>
            @endforelse

            @forelse($tickets->where('type', 'penukaran') as $t)
            <div x-show="activeTab === 'penukaran'" class="rounded-xl shadow-soft border border-slate-100 p-4 flex flex-col relative overflow-hidden {{ in_array($t->status, ['selesai', 'menunggu_validasi']) ? 'bg-slate-50 opacity-70 grayscale-[50%]' : 'bg-white' }}" style="display: none;">
                <div class="flex justify-between items-start mb-3 relative">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600"><i data-lucide="refresh-cw" class="w-4 h-4"></i></div>
                    <div class="absolute top-0 right-0">
                        <input type="checkbox" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500 cursor-pointer w-4 h-4" value="{{ $t->id }}" x-model="selectedIds">
                    </div>
                    @php $stColor = ['menunggu_diisi'=>'amber','menunggu_validasi'=>'blue','selesai'=>'emerald'][$t->status] ?? 'slate'; $stLabel = ['menunggu_diisi'=>'Menunggu Diisi','menunggu_validasi'=>'Menunggu Validasi','selesai'=>'Selesai'][$t->status] ?? $t->status; @endphp
                    <span class="mr-6 px-2 py-1 bg-{{ $stColor }}-100 text-{{ $stColor }}-700 text-[9px] font-bold uppercase rounded-md border border-{{ $stColor }}-200">{{ $stLabel }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] text-slate-500 font-mono mb-1">{{ $t->ticket_code }}</p>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Penukaran Aset</h3>
                    <div class="flex items-center text-[10px] text-slate-500"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ $t->created_at->diffForHumans() }}</div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button onclick="copyLink('{{ $baseUrl }}/tiket/penukaran?id={{ $t->ticket_code }}')"
                        class="flex-1 flex items-center justify-center px-3 py-2 bg-slate-50 text-slate-600 font-semibold rounded-lg text-xs border border-slate-200 hover:bg-slate-100">
                        <i data-lucide="copy" class="w-3 h-3 mr-1.5"></i> Salin Link
                    </button>
                    <form action="{{ route('ticket.destroy', $t->id) }}" method="POST" @submit.prevent="triggerConfirm($event.target)">
                        @csrf @method('DELETE')
                        <button class="flex items-center justify-center px-3 py-2 bg-red-50 text-red-600 font-semibold rounded-lg text-xs border border-red-100 hover:bg-red-100">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div x-show="activeTab === 'penukaran'" class="col-span-full py-16 text-center text-slate-400 text-sm" style="display:none">
                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-40"></i>
                <p>Belum ada tiket Penukaran. Buat tiket baru.</p>
            </div>
            @endforelse
        </div>

        {{-- GENERATE TIKET MODAL --}}
        <div x-show="openModal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
             style="display: none;">
            <div @click.away="openModal = false" class="bg-white w-full max-w-md flex flex-col rounded-2xl shadow-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-800">Buat Tiket Baru</h3>
                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                <div class="p-4 md:p-6">
                    <p class="text-sm text-slate-500 mb-5">Link form tiket unik akan tersimpan ke database dan siap dibagikan ke karyawan.</p>
                    <form action="{{ route('ticket.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="type" :value="activeTab === 'serah-terima' ? 'serah_terima' : activeTab">

                        <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex items-center">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-[#1d4ed8] shadow-sm mr-3 shrink-0">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Jenis Tiket</p>
                                <p class="font-bold text-[#1d4ed8]" x-text="activeTab === 'serah-terima' ? 'Serah Terima Aset' : (activeTab === 'peminjaman' ? 'Peminjaman Aset' : 'Penukaran Aset')"></p>
                            </div>
                        </div>

                        <template x-if="activeTab === 'peminjaman'">
                            <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl">
                                <span class="text-sm font-semibold text-amber-900 block mb-2">Pilih Tipe Peminjaman</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="borrow_type" value="dalam" checked class="w-4 h-4 text-[#1d4ed8]">
                                        <span class="ml-2 text-sm text-amber-800">Pinjam Dalam</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="borrow_type" value="luar" class="w-4 h-4 text-[#1d4ed8]">
                                        <span class="ml-2 text-sm text-amber-800">Pinjam Luar</span>
                                    </label>
                                </div>
                            </div>
                        </template>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white px-5 py-2.5 rounded-xl font-medium shadow-soft transition-all flex justify-center items-center text-sm">
                                <i data-lucide="link" class="w-4 h-4 mr-2"></i>
                                Buat Tiket & Generate Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!-- CONFIRMATION MODAL -->
    <div x-show="openConfirmModal" 
         class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
         style="display: none;">
        <div @click.away="openConfirmModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-fade-in text-center p-4 md:p-6">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8 text-rose-500"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Batalkan Tiket?</h3>
            <p class="text-sm text-slate-500 mb-6">Yakin ingin membatalkan dan menghapus tiket ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="flex space-x-3">
                <button type="button" @click="openConfirmModal = false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="button" @click="submitPending()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 transition-colors shadow-soft">Lanjut ke PIN</button>
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
            <p class="text-sm text-slate-500 mb-4">Masukkan 6-digit PIN untuk menghapus <span class="font-bold text-slate-700" x-text="deleteMode === 'bulk' ? selectedIds.length + ' tiket terpilih' : '1 tiket'"></span>.</p>
            
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

    <script>
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                // Simple toast
                const toast = document.createElement('div');
                toast.textContent = 'Link disalin!';
                toast.className = 'fixed bottom-5 right-5 z-[999] bg-slate-800 text-white text-sm px-4 py-2 rounded-xl shadow-lg transition-opacity duration-300';
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 2000);
            });
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('tiketApp', () => ({
                activeTab: new URLSearchParams(window.location.search).get('type') || 'serah-terima',
                openModal: false,
                openConfirmModal: false,
                pendingForm: null,
                selectedIds: [],
                openPinModal: false,
                deleteMode: 'bulk', // 'single' or 'bulk'
                pinInput: '',
                pinError: '',
                deleteProcessing: false,
                triggerConfirm(formEl) {
                    this.pendingForm = formEl;
                    this.openConfirmModal = true;
                },
                submitPending() {
                    if(this.pendingForm) {
                        this.openConfirmModal = false;
                        let formAction = this.pendingForm.action;
                        // extract ID from action URL: /workspaceinventory/tiket/ID
                        let parts = formAction.split('/');
                        let id = parts[parts.length - 1];
                        this.pendingForm = id; // Store ID instead of form element
                        this.openDeletePinModal('single');
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
                        idsToDelete = [this.pendingForm];
                    } else {
                        idsToDelete = this.selectedIds;
                    }

                    fetch('/workspaceinventory/tiket/bulk-delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ ids: idsToDelete, pin: this.pinInput })
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            window.location.href = window.location.pathname + '?type=' + this.activeTab;
                        } else {
                            this.pinError = data.message;
                            this.deleteProcessing = false;
                        }
                    }).catch(e => {
                        this.pinError = 'Terjadi kesalahan sistem';
                        this.deleteProcessing = false;
                    });
                }
            }));
        });
    </script>
</x-layout>
