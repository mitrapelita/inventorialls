@extends('user.layout')

@section('content')

<div class="pt-2 sm:pt-6 pb-10 flex flex-col items-center">

    @if(isset($tickets) && $tickets->count() > 0)
    <!-- Active Tickets Grid -->
    <div class="w-full max-w-4xl px-2 sm:px-0 mb-12">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            @foreach($tickets as $ticket)
                @php
                    $urlType = str_replace('_', '-', $ticket->type);
                    $url = route('user.' . $urlType) . '?id=' . $ticket->ticket_code;
                    $isSelesai = $ticket->status === 'selesai' || $ticket->status === 'dibatalkan';
                @endphp
                
                @if(!$isSelesai)
                <!-- Card Menunggu Diisi -->
                <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-[0_2px_10px_rgb(0,0,0,0.03)] border border-slate-100 flex flex-col h-full relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Menunggu Diisi</span>
                    </div>
                    
                    <div class="flex-1">
                        <p class="text-[10px] text-slate-400 font-mono tracking-wider mb-1">ID: {{ $ticket->ticket_code }}</p>
                        <h3 class="text-base font-bold text-slate-800 leading-snug mb-3 capitalize">{{ str_replace('_', ' ', $ticket->type) }} Aset</h3>
                        
                        <div class="space-y-1.5 mb-4">
                            <p class="text-xs text-slate-500 flex items-center"><i data-lucide="calendar" class="w-3.5 h-3.5 mr-2 opacity-70"></i> {{ $ticket->created_at->diffForHumans() }}</p>
                            <p class="text-xs text-slate-500 flex items-center"><i data-lucide="user" class="w-3.5 h-3.5 mr-2 opacity-70"></i> Admin IT</p>
                        </div>
                    </div>
                    
                    <a href="{{ $url }}" class="w-full bg-[#3b82f6] hover:bg-blue-600 text-white font-medium text-sm py-2 sm:py-2.5 rounded-xl flex items-center justify-center transition-colors">
                        Isi Form <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
                @else
                <!-- Card Selesai -->
                <div class="bg-slate-50 rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 flex flex-col h-full relative opacity-70 grayscale-[30%]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-8 h-8 bg-slate-200 text-slate-500 rounded-lg flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                        </div>
                        <span class="bg-slate-200 text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">{{ $ticket->status }}</span>
                    </div>
                    
                    <div class="flex-1">
                        <p class="text-[10px] text-slate-400 font-mono tracking-wider mb-1">ID: {{ $ticket->ticket_code }}</p>
                        <h3 class="text-base font-bold text-slate-700 leading-snug mb-3 capitalize">{{ str_replace('_', ' ', $ticket->type) }} Aset</h3>
                        
                        <div class="space-y-1.5 mb-4">
                            <p class="text-xs text-slate-500 flex items-center">Diisi pada: {{ $ticket->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="w-full bg-slate-100 text-slate-400 font-medium text-sm py-2.5 rounded-xl flex items-center justify-center cursor-not-allowed">
                        Tiket Ditutup
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Greeting (Optional small text to balance the top) -->
    <div class="w-full max-w-4xl text-center mb-8 hidden sm:block">
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pilih Layanan</h2>
        <p class="text-slate-500 mt-2">Silakan pilih menu transaksi inventaris di bawah ini.</p>
    </div>

    <!-- Grid Section for 4 Menu Items -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 relative z-10 w-full max-w-5xl px-2 sm:px-0">
        
        <!-- Card 1: Serah Terima -->
        <a href="{{ route('user.serah-terima') }}" class="bg-white rounded-[20px] sm:rounded-[32px] p-4 sm:p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-row sm:flex-col items-center sm:justify-center text-left sm:text-center transition-all hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group sm:aspect-[4/5]">
            <div class="flex-shrink-0 flex items-center justify-center relative w-16 h-16 sm:w-full sm:h-auto sm:flex-1 mr-4 sm:mr-0">
                <!-- Circular decorative ring (Desktop only) -->
                <div class="hidden sm:block absolute inset-0 m-auto w-24 h-24 rounded-full border-t-2 border-r-2 border-blue-500 opacity-20 transform -rotate-45 group-hover:rotate-0 transition-all duration-500"></div>
                <!-- Icon -->
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-full flex items-center justify-center shadow-inner relative z-10">
                    <i data-lucide="user-plus" class="w-6 h-6 sm:w-8 sm:h-8 drop-shadow-sm"></i>
                </div>
            </div>
            <div class="sm:mt-4 w-full flex-1">
                <h3 class="font-bold text-slate-800 text-sm sm:text-base mb-0.5 sm:mb-1">Serah Terima</h3>
                <p class="text-[11px] sm:text-xs text-slate-400 font-medium leading-tight">Terima Aset Baru</p>
            </div>
        </a>

        <!-- Card 2: Peminjaman -->
        <a href="{{ route('user.peminjaman') }}" class="bg-white rounded-[20px] sm:rounded-[32px] p-4 sm:p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-row sm:flex-col items-center sm:justify-center text-left sm:text-center transition-all hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group sm:aspect-[4/5]">
            <div class="flex-shrink-0 flex items-center justify-center relative w-16 h-16 sm:w-full sm:h-auto sm:flex-1 mr-4 sm:mr-0">
                <div class="hidden sm:block absolute inset-0 m-auto w-24 h-24 rounded-full border-t-2 border-r-2 border-amber-500 opacity-20 transform -rotate-45 group-hover:rotate-0 transition-all duration-500"></div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-100 to-amber-50 text-amber-600 rounded-full flex items-center justify-center shadow-inner relative z-10">
                    <i data-lucide="laptop" class="w-6 h-6 sm:w-8 sm:h-8 drop-shadow-sm"></i>
                </div>
            </div>
            <div class="sm:mt-4 w-full flex-1">
                <h3 class="font-bold text-slate-800 text-sm sm:text-base mb-0.5 sm:mb-1">Peminjaman</h3>
                <p class="text-[11px] sm:text-xs text-slate-400 font-medium leading-tight">Ajukan Peminjaman</p>
            </div>
        </a>

        <!-- Card 3: Penukaran -->
        <a href="{{ route('user.penukaran') }}" class="bg-white rounded-[20px] sm:rounded-[32px] p-4 sm:p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-row sm:flex-col items-center sm:justify-center text-left sm:text-center transition-all hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group sm:aspect-[4/5]">
            <div class="flex-shrink-0 flex items-center justify-center relative w-16 h-16 sm:w-full sm:h-auto sm:flex-1 mr-4 sm:mr-0">
                <div class="hidden sm:block absolute inset-0 m-auto w-24 h-24 rounded-full border-t-2 border-r-2 border-emerald-500 opacity-20 transform -rotate-45 group-hover:rotate-0 transition-all duration-500"></div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shadow-inner relative z-10">
                    <i data-lucide="refresh-cw" class="w-6 h-6 sm:w-8 sm:h-8 drop-shadow-sm"></i>
                </div>
            </div>
            <div class="sm:mt-4 w-full flex-1">
                <h3 class="font-bold text-slate-800 text-sm sm:text-base mb-0.5 sm:mb-1">Penukaran</h3>
                <p class="text-[11px] sm:text-xs text-slate-400 font-medium leading-tight">Tukar Aset Rusak</p>
            </div>
        </a>

        <!-- Card 4: Cek Aset -->
        <a href="{{ route('user.cek-aset') }}" class="bg-white rounded-[20px] sm:rounded-[32px] p-4 sm:p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-row sm:flex-col items-center sm:justify-center text-left sm:text-center transition-all hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group sm:aspect-[4/5]">
            <div class="flex-shrink-0 flex items-center justify-center relative w-16 h-16 sm:w-full sm:h-auto sm:flex-1 mr-4 sm:mr-0">
                <div class="hidden sm:block absolute inset-0 m-auto w-24 h-24 rounded-full border-t-2 border-r-2 border-indigo-500 opacity-20 transform -rotate-45 group-hover:rotate-0 transition-all duration-500"></div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 rounded-full flex items-center justify-center shadow-inner relative z-10">
                    <i data-lucide="search" class="w-6 h-6 sm:w-8 sm:h-8 drop-shadow-sm"></i>
                </div>
            </div>
            <div class="sm:mt-4 w-full flex-1">
                <h3 class="font-bold text-slate-800 text-sm sm:text-base mb-0.5 sm:mb-1">Cek Aset</h3>
                <p class="text-[11px] sm:text-xs text-slate-400 font-medium leading-tight">Validasi Data Fisik</p>
            </div>
        </a>

    </div>

</div>

@endsection
