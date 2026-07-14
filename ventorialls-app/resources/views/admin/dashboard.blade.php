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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('transaksi') }}?tab=serah-terima" class="bg-white p-4 rounded-2xl shadow-soft border border-slate-100 flex items-center hover:border-[#1d4ed8] hover:shadow-md transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1d4ed8] flex items-center justify-center mr-3 flex-shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-slate-800 truncate">Serah Terima</h4>
                    <p class="text-[10px] text-slate-400 truncate">Penyerahan Aset</p>
                </div>
            </a>
            <a href="{{ route('transaksi') }}?tab=peminjaman" class="bg-white p-4 rounded-2xl shadow-soft border border-slate-100 flex items-center hover:border-amber-500 hover:shadow-md transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mr-3 flex-shrink-0 group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="arrow-left-right" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-slate-800 truncate">Pinjam & Kembali</h4>
                    <p class="text-[10px] text-slate-400 truncate">Peminjaman Aset</p>
                </div>
            </a>
            <a href="{{ route('transaksi') }}?tab=penukaran" class="bg-white p-4 rounded-2xl shadow-soft border border-slate-100 flex items-center hover:border-emerald-500 hover:shadow-md transition-all group cursor-pointer">
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
        <!-- Real Bar Chart -->
        <div class="flex-1 w-full overflow-x-auto flex flex-col pb-4">
            @php $max_chart = max(10, count($chart_values) > 0 ? max($chart_values) : 0); @endphp
            <div class="flex-1 flex items-end justify-between space-x-2 pt-8 border-b border-slate-100 pb-2 relative min-w-[400px] mt-4">
                <div class="absolute top-0 w-full border-t border-slate-100 border-dashed text-[10px] text-slate-400">{{ $max_chart }}</div>
                <div class="absolute top-1/2 w-full border-t border-slate-100 border-dashed text-[10px] text-slate-400 transform -translate-y-1/2">{{ ceil($max_chart / 2) }}</div>
            
                @if(count($chart_values) > 0 && array_sum($chart_values) > 0)
                    @foreach($chart_values as $index => $value)
                        <div class="w-1/7 flex flex-col items-center group relative z-10 w-full max-w-[40px]">
                            <div class="w-full bg-[#1d4ed8] rounded-t-md hover:bg-[#1e40af] transition-all relative" 
                                 style="height: {{ ($value / $max_chart) * 100 }}%; min-height: {{ $value > 0 ? '4px' : '0px' }}">
                                <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1 px-2 rounded transition-opacity whitespace-nowrap z-20 pointer-events-none">
                                    {{ $value }}
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 mt-2 truncate">{{ $chart_labels[$index] }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-sm text-slate-400 font-medium">Belum ada data chart.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity Area -->
    <div class="col-span-1 bg-white rounded-2xl shadow-soft border border-slate-100 p-5 flex flex-col h-[400px]">
        <h3 class="text-base font-bold text-slate-700 mb-4 shrink-0">Log Aktivitas Terbaru</h3>
        <div class="flex-1 overflow-y-auto space-y-4 pr-1 mb-4">
            @forelse($recent_activities as $log)
            <div class="flex items-start">
                @php
                    $icon = 'activity';
                    $color = 'blue';
                    if($log->action == 'created' || $log->action == 'approved') { $icon = 'check-circle'; $color = 'emerald'; }
                    elseif($log->action == 'deleted' || $log->action == 'rejected') { $icon = 'x-circle'; $color = 'rose'; }
                    elseif($log->action == 'updated') { $icon = 'edit'; $color = 'amber'; }
                @endphp
                <div class="w-8 h-8 rounded-full bg-{{$color}}-50 flex items-center justify-center mr-3 shrink-0">
                    <i data-lucide="{{ $icon }}" class="w-4 h-4 text-{{$color}}-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800">{{ $log->admin->name ?? $log->admin->nama ?? 'Sistem' }}</p>
                    <p class="text-[11px] text-slate-500 truncate" title="{{ $log->description }}">{{ $log->description }}</p>
                    <p class="text-[9px] text-slate-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="flex items-center justify-center h-full">
                <p class="text-sm text-slate-400 font-medium">Belum ada log aktivitas.</p>
            </div>
            @endforelse
        </div>
        <a href="{{ route('log') }}" class="mt-auto block w-full text-center bg-slate-50 hover:bg-slate-100 text-[#1d4ed8] text-xs font-semibold py-2.5 rounded-xl transition-colors shrink-0 border border-slate-200">
            Lihat Selengkapnya
        </a>
    </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6">
        <!-- Chart Area for Barang Rusak -->
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-5 flex flex-col overflow-hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-slate-700">Grafik Barang Rusak</h3>
                <form action="{{ route('dashboard') }}" method="GET" class="m-0">
                    <select name="filter_rusak" onchange="this.form.submit()" class="px-3 py-1 rounded border border-slate-200 bg-slate-50 text-xs font-medium outline-none">
                        <option value="harian" {{ $filter_rusak == 'harian' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="mingguan" {{ $filter_rusak == 'mingguan' ? 'selected' : '' }}>4 Minggu Terakhir</option>
                        <option value="bulanan" {{ $filter_rusak == 'bulanan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                        <option value="tahunan" {{ $filter_rusak == 'tahunan' ? 'selected' : '' }}>5 Tahun Terakhir</option>
                    </select>
                </form>
            </div>
            <!-- Real Bar Chart -->
            <div class="flex-1 w-full overflow-x-auto flex flex-col pb-4">
                @php $max_rusak = max(10, count($rusak_values) > 0 ? max($rusak_values) : 0); @endphp
                <div class="flex-1 flex items-end justify-between space-x-2 pt-8 border-b border-slate-100 pb-2 relative min-w-[400px] mt-4 min-h-[200px]">
                    <div class="absolute top-0 w-full border-t border-slate-100 border-dashed text-[10px] text-slate-400">{{ $max_rusak }}</div>
                    <div class="absolute top-1/2 w-full border-t border-slate-100 border-dashed text-[10px] text-slate-400 transform -translate-y-1/2">{{ ceil($max_rusak / 2) }}</div>
                
                    @if(count($rusak_values) > 0 && array_sum($rusak_values) > 0)
                        @foreach($rusak_values as $index => $value)
                            <div class="flex-1 flex flex-col items-center group relative z-10 max-w-[50px] mx-auto w-full">
                                <div class="w-full bg-rose-500 rounded-t-md hover:bg-rose-600 transition-all relative" 
                                     style="height: {{ ($value / $max_rusak) * 100 }}%; min-height: {{ $value > 0 ? '4px' : '0px' }}">
                                    <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1 px-2 rounded transition-opacity whitespace-nowrap z-20 pointer-events-none">
                                        {{ $value }}
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-2 truncate">{{ $rusak_labels[$index] }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm text-slate-400 font-medium">Belum ada data barang rusak.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>

