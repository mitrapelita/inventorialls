<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ventorialls Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-biru.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 40% 20%, hsla(228,100%,74%,0.15) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(189,100%,56%,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(355,100%,93%,0.1) 0px, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4 antialiased text-slate-800">

    <div class="w-full max-w-5xl flex rounded-3xl shadow-2xl overflow-hidden glass-card">
        
        <!-- Left Side: Branding / Visual (Hidden on mobile) -->
        <div class="hidden lg:flex w-1/2 bg-[#0f172a] p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#1d4ed8] rounded-full mix-blend-screen filter blur-[80px] opacity-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#3b82f6] rounded-full mix-blend-screen filter blur-[80px] opacity-30 animate-pulse" style="animation-delay: 2s;"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/20">
                        <i data-lucide="box" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">Ventorialls.</span>
                </div>
                
                <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                    Enterprise IT<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#60a5fa] to-[#3b82f6]">Inventory System</span>
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed max-w-md">
                    Kelola seluruh siklus hidup aset IT perusahaan Anda dengan lebih cerdas, aman, dan terstruktur.
                </p>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center flex-shrink-0 text-white shadow-lg">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-white text-sm font-bold">Secure Access</p>
                        <p class="text-slate-400 text-xs">Akses terbatas hanya untuk administrator IT.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 bg-white p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
            
            <div class="lg:hidden flex items-center gap-2 mb-8 justify-center">
                <div class="w-8 h-8 bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] rounded-lg flex items-center justify-center text-white shadow-sm border border-[#1d4ed8]">
                    <i data-lucide="box" class="w-4 h-4"></i>
                </div>
                <span class="text-lg font-bold text-slate-800 tracking-tight">Ventorialls.</span>
            </div>

            <div class="mb-8 text-center lg:text-left">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-2">Selamat Datang 👋</h2>
                <p class="text-slate-500 text-sm">Silakan masuk menggunakan kredensial admin Anda.</p>
            </div>

            {{-- Error Message --}}
            @if ($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Username Input -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Username / ID Karyawan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input type="text" name="id_karyawan" required class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] bg-slate-50 focus:bg-white outline-none transition-all placeholder-slate-400">
                    </div>
                </div>

                <!-- Password Input -->
                <div x-data="{ showPassword: false }">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-sm font-bold text-slate-700">Kata Sandi</label>
                        <a href="#" class="text-xs font-semibold text-[#1d4ed8] hover:text-[#1e40af] transition-colors">Lupa sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" required class="block w-full pl-10 pr-10 py-2.5 sm:py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] bg-slate-50 focus:bg-white outline-none transition-all placeholder-slate-400">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="eye" x-show="!showPassword" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" x-show="showPassword" class="w-5 h-5" style="display: none;"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-[#1d4ed8] focus:ring-[#1d4ed8] border-gray-300 rounded cursor-pointer">
                    <label for="remember-me" class="ml-2 block text-sm text-slate-600 cursor-pointer">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:from-[#1e40af] hover:to-[#2563eb] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1d4ed8] transition-all transform active:scale-[0.98]">
                        Masuk Dashboard
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-xs text-slate-400 font-medium">
                &copy; 2026 PT MPTB - Secured System.
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Initialize Lucide icons
            lucide.createIcons();
        });
    </script>
</body>
</html>
