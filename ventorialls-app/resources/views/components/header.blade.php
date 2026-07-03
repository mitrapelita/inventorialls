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
        <button class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition-colors relative">
            <i data-lucide="bell" class="w-4 h-4 md:w-5 md:h-5"></i>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
    </div>
</header>
