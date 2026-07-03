@props(['active' => 'master'])

<!-- Backdrop for mobile -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/50 z-30 md:hidden" 
     style="display: none;"></div>

<!-- Sidebar -->
<aside x-data="{ isHovered: false }" 
       @mouseenter="isHovered = true" 
       @mouseleave="isHovered = false"
       class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] text-white flex flex-col h-full md:rounded-r-3xl shadow-soft z-40 flex-shrink-0 fixed inset-y-0 left-0 transform transition-all duration-300 md:relative overflow-hidden"
       :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0 ' + (isHovered ? 'md:w-64' : 'md:w-20')">
    
    <div class="h-16 md:h-20 flex items-center px-6 md:px-6 border-b border-white/10 justify-between whitespace-nowrap">
        <div class="flex items-center">
            <i data-lucide="package-search" class="w-7 h-7 md:w-8 md:h-8 mr-3 flex-shrink-0" :class="!isHovered && !sidebarOpen ? 'ml-0.5' : ''"></i>
            <h1 class="text-lg md:text-xl font-bold tracking-wide transition-opacity duration-300" x-show="isHovered || sidebarOpen" x-transition.opacity>Ventorialls</h1>
        </div>
        <!-- Close button for mobile -->
        <button @click="sidebarOpen = false" class="md:hidden text-blue-200 hover:text-white focus:outline-none flex-shrink-0">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto overflow-x-hidden text-sm [&::-webkit-scrollbar]:hidden" style="scrollbar-width: none;">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'dashboard' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Dashboard</span>
        </a>
        <a href="{{ route('transaksi') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'transaksi' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="arrow-left-right" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Transaksi</span>
        </a>
        <a href="{{ route('validasi') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'validasi' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="clipboard-check" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Validasi</span>
        </a>
        <a href="{{ route('mutasi') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'mutasi' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="arrow-down-up" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Log Inventaris</span>
        </a>
        <a href="{{ route('laporan') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'laporan' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="history" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Histori Barang</span>
        </a>
        <a href="{{ route('log') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'log' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="activity" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Log Aktivitas</span>
        </a>
        <a href="{{ route('karyawan') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'karyawan' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="users" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Data Karyawan</span>
        </a>
        <a href="{{ route('master') }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-colors {{ $active === 'master' ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}" :class="!isHovered && !sidebarOpen ? 'justify-center px-0' : ''">
            <i data-lucide="database" class="w-5 h-5 flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="isHovered || sidebarOpen" class="whitespace-nowrap" x-transition.opacity>Master Data</span>
        </a>
    </nav>
    
    <div class="p-4 border-t border-white/10 overflow-hidden whitespace-nowrap">
        <div class="flex items-center py-2" :class="isHovered || sidebarOpen ? 'px-4' : 'justify-center'">
            <div class="w-10 h-10 rounded-full bg-white text-[#1d4ed8] flex items-center justify-center font-bold text-lg flex-shrink-0" :class="isHovered || sidebarOpen ? 'mr-3' : ''">AD</div>
            <div class="overflow-hidden" x-show="isHovered || sidebarOpen" x-transition.opacity>
                <p class="text-sm font-semibold truncate">Admin Inventory</p>
                <p class="text-xs text-blue-200 truncate">admin@ventorialls.co</p>
            </div>
        </div>
    </div>
</aside>

