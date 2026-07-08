<x-layout active="dashboard" headerTitle="Dashboard Statistik">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 relative">
    
    <!-- Card 1: Aktif -->
    <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false" @click="hover = !hover" class="relative bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex flex-col transition-all cursor-pointer hover:border-emerald-200 hover:shadow-md z-10 hover:z-50">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm border border-emerald-100">
                <i data-lucide="shield-check" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <div>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $stats['aset_aktif'] ?? 0 }}</h3>
            </div>
        </div>
        <!-- Hover Breakdown -->
        <div x-show="hover" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="absolute top-[105%] left-0 w-full bg-white rounded-xl shadow-xl border border-slate-100 p-4 z-50 text-xs text-slate-600 space-y-2" style="display: none;">
            @forelse($breakdown_aktif as $kategori => $count)
                <div class="flex justify-between items-center pb-2 border-b border-slate-50 last:border-0 last:pb-0">
                    <span class="font-medium text-slate-600">{{ $kategori ?: 'Tanpa Kategori' }}</span>
                    <span class="bg-emerald-50 text-emerald-600 py-0.5 px-2 rounded-md font-bold">{{ $count }}</span>
                </div>
            @empty
                <div class="flex items-center justify-center py-2"><span class="text-xs text-slate-400 font-medium italic">Tidak ada data</span></div>
            @endforelse
        </div>
    </div>

    <!-- Card 2: Tersedia -->
    <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false" @click="hover = !hover" class="relative bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex flex-col transition-all cursor-pointer hover:border-amber-200 hover:shadow-md z-10 hover:z-50">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 text-amber-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm border border-amber-100">
                <i data-lucide="layers" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <div>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Tersedia</p>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $stats['aset_disimpan'] ?? 0 }}</h3>
            </div>
        </div>
        <!-- Hover Breakdown -->
        <div x-show="hover" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="absolute top-[105%] left-0 w-full bg-white rounded-xl shadow-xl border border-slate-100 p-4 z-50 text-xs text-slate-600 space-y-2" style="display: none;">
            @forelse($breakdown_tersedia as $kategori => $count)
                <div class="flex justify-between items-center pb-2 border-b border-slate-50 last:border-0 last:pb-0">
                    <span class="font-medium text-slate-600">{{ $kategori ?: 'Tanpa Kategori' }}</span>
                    <span class="bg-amber-50 text-amber-500 py-0.5 px-2 rounded-md font-bold">{{ $count }}</span>
                </div>
            @empty
                <div class="flex items-center justify-center py-2"><span class="text-xs text-slate-400 font-medium italic">Tidak ada data</span></div>
            @endforelse
        </div>
    </div>

    <!-- Card 3: Return Vendor -->
    <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false" @click="hover = !hover" class="relative bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex flex-col transition-all cursor-pointer hover:border-red-200 hover:shadow-md z-10 hover:z-50">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-50 text-red-600 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm border border-red-100">
                <i data-lucide="truck" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <div>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Return Vendor</p>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $stats['return_vendor'] ?? 0 }}</h3>
            </div>
        </div>
        <!-- Hover Breakdown -->
        <div x-show="hover" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="absolute top-[105%] left-0 w-full bg-white rounded-xl shadow-xl border border-slate-100 p-4 z-50 text-xs text-slate-600 space-y-2" style="display: none;">
            @forelse($breakdown_return as $kategori => $count)
                <div class="flex justify-between items-center pb-2 border-b border-slate-50 last:border-0 last:pb-0">
                    <span class="font-medium text-slate-600">{{ $kategori ?: 'Tanpa Kategori' }}</span>
                    <span class="bg-red-50 text-red-600 py-0.5 px-2 rounded-md font-bold">{{ $count }}</span>
                </div>
            @empty
                <div class="flex items-center justify-center py-2"><span class="text-xs text-slate-400 font-medium italic">Tidak ada data</span></div>
            @endforelse
        </div>
    </div>

    <!-- Card 4: Rusak Disimpan -->
    <div x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false" @click="hover = !hover" class="relative bg-white rounded-2xl p-5 shadow-soft border border-slate-100 flex flex-col transition-all cursor-pointer hover:border-rose-200 hover:shadow-md z-10 hover:z-50">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-100 to-rose-50 text-rose-600 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm border border-rose-100">
                <i data-lucide="wrench" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <div>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Rusak Disimpan</p>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $stats['aset_rusak'] ?? 0 }}</h3>
            </div>
        </div>
        <!-- Hover Breakdown -->
        <div x-show="hover" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="absolute top-[105%] left-0 w-full bg-white rounded-xl shadow-xl border border-slate-100 p-4 z-50 text-xs text-slate-600 space-y-2" style="display: none;">
            @forelse($breakdown_rusak as $kategori => $count)
                <div class="flex justify-between items-center pb-2 border-b border-slate-50 last:border-0 last:pb-0">
                    <span class="font-medium text-slate-600">{{ $kategori ?: 'Tanpa Kategori' }}</span>
                    <span class="bg-rose-50 text-rose-600 py-0.5 px-2 rounded-md font-bold">{{ $count }}</span>
                </div>
            @empty
                <div class="flex items-center justify-center py-2"><span class="text-xs text-slate-400 font-medium italic">Tidak ada data</span></div>
            @endforelse
        </div>
    </div>
    </div>

    <!-- Quick Access Kategori Transaksi -->
    <div class="mb-6">
        <h3 class="text-sm font-bold text-slate-700 mb-3">Akses Cepat Transaksi</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('transaksi') }}" class="bg-white p-4 rounded-2xl shadow-soft border border-slate-100 flex items-center hover:border-[#1d4ed8] hover:shadow-md transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1d4ed8] flex items-center justify-center mr-3 flex-shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-slate-800 truncate">Serah Terima</h4>
                    <p class="text-[10px] text-slate-400 truncate">Penyerahan Aset</p>
                </div>
            </a>
            <a href="{{ route('transaksi') }}" class="bg-white p-4 rounded-2xl shadow-soft border border-slate-100 flex items-center hover:border-amber-500 hover:shadow-md transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mr-3 flex-shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="arrow-left-right" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-slate-800 truncate">Pinjam & Kembali</h4>
                    <p class="text-[10px] text-slate-400 truncate">Peminjaman Aset</p>
                </div>
            </a>
            <a href="{{ route('transaksi') }}" class="bg-white p-4 rounded-2xl shadow-soft border border-slate-100 flex items-center hover:border-emerald-500 hover:shadow-md transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mr-3 flex-shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-slate-800 truncate">Penukaran</h4>
                    <p class="text-[10px] text-slate-400 truncate">Ganti Aset Rusak</p>
                </div>
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart Area -->
    <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl shadow-soft border border-slate-100 p-5 flex flex-col overflow-hidden">
        <div class="flex justify-between items-center mb-4">
        <h3 class="text-base font-bold text-slate-700">Data Log Aktivitas</h3>
        <select class="px-3 py-1 rounded border border-slate-200 bg-slate-50 text-xs font-medium outline-none">
            <option>Hari ini</option>
            <option>Minggu ini</option>
            <option>Bulan ini</option>
            <option>Tahun ini</option>
        </select>
        </div>
        <!-- Simulated Bar Chart -->
        <div class="flex-1 w-full overflow-x-auto">
            <div class="flex items-end justify-between space-x-2 pt-6 h-48 border-b border-slate-100 pb-2 relative min-w-[400px]">
                <div class="absolute top-0 w-full border-t border-slate-100 border-dashed text-[10px] text-slate-400">100</div>
                <div class="absolute top-1/2 w-full border-t border-slate-100 border-dashed text-[10px] text-slate-400 transform -translate-y-1/2">50</div>
            
            <!-- Bars removed for real data -->
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-sm text-slate-400 font-medium">Belum ada data chart.</span>
            </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Area -->
    <div class="col-span-1 bg-white rounded-2xl shadow-soft border border-slate-100 p-5 flex flex-col">
        <h3 class="text-base font-bold text-slate-700 mb-4">Log Aktivitas Terbaru</h3>
        <div class="flex-1 overflow-y-auto space-y-4">
        
        <div class="flex items-center justify-center h-full">
            <p class="text-sm text-slate-400 font-medium">Belum ada log aktivitas.</p>
        </div>

        </div>
    </div>
    </div>
</x-layout>

