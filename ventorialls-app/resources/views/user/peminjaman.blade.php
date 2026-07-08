@extends('user.layout')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    @forelse($tickets as $t)
        @php
            $isClosed = in_array($t->status, ['selesai', 'dibatalkan', 'menunggu_validasi']);
            $statusLabel = $t->status === 'menunggu_diisi' ? 'Menunggu Diisi' : ($t->status === 'menunggu_validasi' ? 'Diproses Admin' : 'Selesai');
            $statusColor = $t->status === 'menunggu_diisi' ? 'amber' : ($t->status === 'menunggu_validasi' ? 'blue' : 'emerald');
        @endphp

        @if(!$isClosed || $t->status === 'menunggu_diisi')
        <!-- Active Ticket -->
        <div class="bg-white rounded-xl shadow-soft border border-slate-100 p-4 flex flex-col hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-[80px] -z-0 opacity-50 group-hover:scale-110 transition-transform"></div>
            
            <div class="flex justify-between items-start mb-3 relative z-10">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                    <i data-lucide="repeat" class="w-4 h-4"></i>
                </div>
                <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 text-[9px] font-bold uppercase rounded-md border border-{{ $statusColor }}-200">
                    {{ $statusLabel }}
                </span>
            </div>
            
            <div class="relative z-10 flex-1">
                <p class="text-[10px] text-slate-500 font-mono mb-1">ID: {{ $t->ticket_code }}</p>
                <h3 class="text-sm font-bold text-slate-800 leading-tight mb-2">Peminjaman Aset</h3>
                <div class="mb-3">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $t->borrow_type === 'luar' ? 'bg-blue-50 text-[#1d4ed8] border border-blue-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                        Pinjam {{ ucfirst($t->borrow_type) }}
                    </span>
                </div>
                <div class="flex items-center text-[10px] text-slate-500 mb-1">
                    <i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ $t->created_at->diffForHumans() }}
                </div>
                <div class="flex items-center text-[10px] text-slate-500">
                    <i data-lucide="user-cog" class="w-3 h-3 mr-1"></i> Admin IT
                </div>
            </div>

            <div class="mt-4 relative z-10">
                <a href="{{ route('tiket', ['type' => 'peminjaman', 'id' => $t->ticket_code]) }}" class="w-full flex items-center justify-center px-3 py-2 bg-gradient-to-br from-amber-500 to-amber-600 text-white font-semibold rounded-lg text-xs transition-all shadow-sm border border-amber-500 hover:shadow-md">
                    <span>Isi Form</span>
                    <i data-lucide="arrow-right" class="w-3 h-3 ml-1.5"></i>
                </a>
            </div>
        </div>
        @else
        <!-- Closed / Filled Ticket -->
        <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 flex flex-col relative overflow-hidden opacity-60 grayscale">
            <div class="flex justify-between items-start mb-3">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-bold uppercase rounded-md border border-emerald-200">
                    {{ $statusLabel }}
                </span>
            </div>
            
            <div class="flex-1">
                <p class="text-[10px] text-slate-500 font-mono mb-1">ID: {{ $t->ticket_code }}</p>
                <h3 class="text-sm font-bold text-slate-800 leading-tight mb-1">Peminjaman Aset</h3>
                <div class="flex items-center text-[10px] text-slate-500 mb-1">
                    Diisi pada: {{ $t->updated_at->format('d M Y') }}
                </div>
            </div>

            <div class="mt-4">
                <button disabled class="w-full flex items-center justify-center px-3 py-2 bg-white text-slate-400 font-semibold rounded-lg text-xs border border-slate-200 cursor-not-allowed">
                    <span>Tiket Ditutup</span>
                </button>
            </div>
        </div>
        @endif
    @empty
        <div class="col-span-full bg-slate-50 border border-slate-200 border-dashed rounded-2xl p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
            </div>
            <h4 class="font-bold text-slate-700 text-lg mb-2">Tidak Ada Tiket Peminjaman</h4>
            <p class="text-sm text-slate-500 max-w-md">
                Belum ada form peminjaman yang ditugaskan oleh Admin.
            </p>
        </div>
    @endforelse

</div>
@endsection
