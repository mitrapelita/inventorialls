<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ventorialls - Karyawan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f7fb] text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('image/logo-ventorialls-2.png') }}" alt="Ventorialls Logo" class="h-8 w-auto object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h1 class="font-medium text-slate-800 leading-tight">Ventorialls</h1>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Karyawan Portal</p>
                    </div>
                </div>

                <!-- Desktop Navigation (Moved into header) -->
                <div class="hidden md:flex bg-white rounded-2xl shadow-sm border border-slate-200 p-1.5 items-center justify-center mx-4 flex-1 max-w-lg">
                    <a href="{{ route('user.dashboard') }}" class="flex-1 flex items-center justify-center py-2 rounded-xl text-[13px] font-semibold transition-all {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-[#1d4ed8] shadow-sm border border-blue-100' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                        <i data-lucide="layout-grid" class="w-4 h-4 mr-1.5"></i> Beranda
                    </a>
                    <a href="{{ route('user.serah-terima') }}" class="flex-1 flex items-center justify-center py-2 rounded-xl text-[13px] font-semibold transition-all {{ request()->routeIs('user.serah-terima') ? 'bg-blue-50 text-[#1d4ed8] shadow-sm border border-blue-100' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                        <i data-lucide="user-plus" class="w-4 h-4 mr-1.5"></i> Serah Terima
                    </a>
                    <a href="{{ route('user.peminjaman') }}" class="flex-1 flex items-center justify-center py-2 rounded-xl text-[13px] font-semibold transition-all {{ request()->routeIs('user.peminjaman') ? 'bg-blue-50 text-[#1d4ed8] shadow-sm border border-blue-100' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                        <i data-lucide="repeat" class="w-4 h-4 mr-1.5"></i> Peminjaman
                    </a>
                    <a href="{{ route('user.penukaran') }}" class="flex-1 flex items-center justify-center py-2 rounded-xl text-[13px] font-semibold transition-all {{ request()->routeIs('user.penukaran') ? 'bg-blue-50 text-[#1d4ed8] shadow-sm border border-blue-100' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                        <i data-lucide="refresh-cw" class="w-4 h-4 mr-1.5"></i> Penukaran
                    </a>
                </div>

                <!-- Profile / Status -->
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800">Hi, Karyawan</p>
                        <p class="text-xs text-emerald-600 font-medium flex items-center justify-end">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span> Online
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu is moved to Bottom Navigation -->
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 max-w-5xl w-full mx-auto p-4 sm:p-6 lg:p-8 relative pb-24 md:pb-8">
        
        @yield('content')
    </main>

    <footer class="mt-auto py-6 text-center text-xs text-slate-400 font-medium hidden md:block">
        &copy; 2026 PT MPTB - IT Inventory Management System
    </footer>

    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around items-center pb-safe z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.02)]">
        <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center justify-center w-full py-3 {{ request()->routeIs('user.dashboard') ? 'text-[#1d4ed8]' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="layout-grid" class="w-5 h-5 mb-1 {{ request()->routeIs('user.dashboard') ? 'fill-blue-100' : '' }}"></i>
            <span class="text-[10px] font-semibold">Beranda</span>
        </a>
        <a href="{{ route('user.serah-terima') }}" class="flex flex-col items-center justify-center w-full py-3 {{ request()->routeIs('user.serah-terima') ? 'text-[#1d4ed8]' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="user-plus" class="w-5 h-5 mb-1 {{ request()->routeIs('user.serah-terima') ? 'fill-blue-100' : '' }}"></i>
            <span class="text-[10px] font-semibold">Serah Terima</span>
        </a>
        <a href="{{ route('user.peminjaman') }}" class="flex flex-col items-center justify-center w-full py-3 {{ request()->routeIs('user.peminjaman') ? 'text-[#1d4ed8]' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="repeat" class="w-5 h-5 mb-1 {{ request()->routeIs('user.peminjaman') ? 'fill-blue-100' : '' }}"></i>
            <span class="text-[10px] font-semibold">Peminjaman</span>
        </a>
        <a href="{{ route('user.penukaran') }}" class="flex flex-col items-center justify-center w-full py-3 {{ request()->routeIs('user.penukaran') ? 'text-[#1d4ed8]' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="refresh-cw" class="w-5 h-5 mb-1 {{ request()->routeIs('user.penukaran') ? 'fill-blue-100' : '' }}"></i>
            <span class="text-[10px] font-semibold">Penukaran</span>
        </a>
    </nav>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'center'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    html: '{!! implode("<br>", $errors->all()) !!}',
                    confirmButtonColor: '#1d4ed8',
                    position: 'center'
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
