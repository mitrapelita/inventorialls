@props(['title'])

<header class="h-16 md:h-20 bg-white shadow-sm flex items-center justify-between px-4 md:px-8 z-10 flex-shrink-0">
    <div class="flex items-center">
        <!-- Hamburger Menu (Mobile Only) -->
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 mr-3 text-slate-500 hover:text-[#1d4ed8] focus:outline-none rounded-lg hover:bg-slate-100 transition-colors">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate max-w-[200px] sm:max-w-md">{{ $title }}</h2>
    </div>
    <div class="flex items-center space-x-4">
        <!-- Search bar hidden on smallest screens -->
        <div class="relative hidden sm:block">
            <i data-lucide="search" class="w-4 h-4 md:w-5 md:h-5 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            <input type="text" placeholder="Cari cepat..." class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-full bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] w-48 md:w-64 transition-all">
        </div>
        <!-- Notification Dropdown -->
        <div class="relative" x-data="{ notifOpen: false }" @click.away="notifOpen = false">
            <button @click="notifOpen = !notifOpen" class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition-colors relative">
                <i data-lucide="bell" class="w-4 h-4 md:w-5 md:h-5"></i>
                @if(isset($pendingValidations) && $pendingValidations->count() > 0)
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse border-2 border-white"></span>
                @endif
            </button>
            
            <div x-cloak x-show="notifOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Notifikasi</h3>
                    @if(isset($pendingValidations) && $pendingValidations->count() > 0)
                        <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingValidations->count() }} Baru</span>
                    @endif
                </div>
                
                <div class="max-h-[300px] overflow-y-auto">
                    @if(isset($pendingValidations) && $pendingValidations->count() > 0)
                        <div class="divide-y divide-slate-50">
                            @foreach($pendingValidations as $notif)
                            <a href="{{ route('validasi') }}" class="flex items-start p-4 hover:bg-slate-50 transition-colors block">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mr-3">
                                    <i data-lucide="{{ $notif->type === 'barang_keluar' ? 'arrow-up-right' : ($notif->type === 'serah_terima' ? 'arrow-down-left' : 'refresh-ccw') }}" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate">
                                        Validasi {{ ucwords(str_replace('_', ' ', $notif->type)) }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5 truncate">Oleh: {{ $notif->nama_pengaju ?? 'User' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1 flex items-center">
                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i> {{ $notif->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                            </div>
                            <p class="text-sm text-slate-500 font-medium">Tidak ada notifikasi baru</p>
                            <p class="text-xs text-slate-400 mt-1">Semua data sudah tervalidasi.</p>
                        </div>
                    @endif
                </div>
                
                @if(isset($pendingValidations) && $pendingValidations->count() > 0)
                <div class="p-3 border-t border-slate-100 text-center">
                    <a href="{{ route('validasi') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">Lihat Semua Validasi</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</header>
