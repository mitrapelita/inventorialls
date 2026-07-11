<x-layout active="laporan" headerTitle="Pelacakan Histori Barang">
    <div x-data="{ searched: false }">
      <!-- Search Bar -->
      <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5 border border-slate-100 mb-6 max-w-3xl">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Cari Riwayat Inventaris</h3>
        <form action="{{ route('laporan') }}" method="GET" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
          <div class="relative flex-1">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Masukkan No. Aset (Cth: UP-LAP-001)" class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none font-mono text-sm uppercase">
          </div>
          <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center justify-center shadow-soft shadow-[#1d4ed8]/30">
            Cari
          </button>
        </form>
      </div>

      <!-- Result Area -->
      @if(request('q'))
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Asset Info -->
        <div class="col-span-1 lg:col-span-1">
          @if($inventory)
          <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5 border border-slate-100 lg:sticky lg:top-8">
            @php
              $iconName = 'laptop';
              $iconClasses = 'bg-blue-50 text-blue-600';
              switch (strtolower(str_replace('_', ' ', $inventory->jenis ?? $inventory->kategori ?? ''))) {
                case 'laptop': $iconName = 'laptop'; $iconClasses = 'bg-blue-50 text-blue-600'; break;
                case 'charger': $iconName = 'battery-charging'; $iconClasses = 'bg-amber-50 text-amber-600'; break;
                case 'mouse': $iconName = 'mouse'; $iconClasses = 'bg-emerald-50 text-emerald-600'; break;
                case 'lan extender': $iconName = 'network'; $iconClasses = 'bg-indigo-50 text-indigo-600'; break;
                case 'headset': $iconName = 'headphones'; $iconClasses = 'bg-rose-50 text-rose-600'; break;
                case 'hp root': case 'smartphone': case 'hp': $iconName = 'smartphone'; $iconClasses = 'bg-purple-50 text-purple-600'; break;
                case 'audio jack': $iconName = 'plug'; $iconClasses = 'bg-orange-50 text-orange-600'; break;
                default: $iconName = 'box'; $iconClasses = 'bg-slate-100 text-slate-600'; break;
              }
            @endphp
            <div class="w-12 h-12 {{ $iconClasses }} rounded-xl flex items-center justify-center mb-4">
              <i data-lucide="{{ $iconName }}" class="w-6 h-6"></i>
            </div>
            
            <h4 class="text-base font-bold text-slate-800 mb-1 capitalize">{{ $inventory->merk }} {{ $inventory->jenis }}</h4>
            <p class="font-mono text-slate-500 text-xs mb-4">{{ $inventory->sn }}</p>
            
            <div class="space-y-3 pt-4 border-t border-slate-100">
              <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Status Terakhir</p>
                <div class="flex items-center mt-1">
                  <span class="w-1.5 h-1.5 rounded-full {{ $inventory->status === 'Aktif' ? 'bg-emerald-500' : 'bg-slate-400' }} mr-2"></span>
                  <span class="text-slate-700 font-medium text-xs">{{ $inventory->status }}</span>
                </div>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Pengguna Saat Ini</p>
                <p class="text-slate-700 font-medium text-xs mt-1">{{ $inventory->pengguna ?: '-' }}</p>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Tanggal Masuk</p>
                <p class="text-slate-700 font-medium text-xs mt-1">{{ $inventory->tanggal_masuk ? $inventory->tanggal_masuk->format('d F Y') : '-' }}</p>
              </div>
            </div>
          </div>
          @else
          <div class="bg-rose-50 rounded-2xl shadow-soft p-4 md:p-5 border border-rose-100 text-center">
            <i data-lucide="alert-circle" class="w-8 h-8 text-rose-400 mx-auto mb-2"></i>
            <h4 class="text-sm font-bold text-rose-700 mb-1">Aset Tidak Ditemukan</h4>
            <p class="text-xs text-rose-600">Pastikan Nomor Aset yang kamu masukkan sudah benar.</p>
          </div>
          @endif
        </div>

        <!-- Timeline -->
        <div class="col-span-1 lg:col-span-2">
          <div class="bg-white rounded-2xl shadow-soft p-4 md:p-5 lg:p-6 border border-slate-100">
            <h3 class="text-sm font-bold text-slate-700 mb-6 border-b pb-3">Riwayat Pergerakan Barang (Timeline)</h3>
            
            @if($inventory && $histori->isNotEmpty())
              <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                @foreach($histori as $item)
                  <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 text-blue-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                      <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border border-slate-100 bg-white shadow-soft group-hover:border-blue-200 transition-colors">
                      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
                        <span class="font-bold text-slate-800 capitalize">{{ str_replace('_', ' ', $item->transaction->type) }}</span>
                        <time class="font-mono text-[10px] text-slate-400 mt-1 sm:mt-0">{{ $item->transaction->created_at->format('d M Y, H:i') }}</time>
                      </div>
                      <p class="text-xs text-slate-600 mb-3">
                        Oleh: <span class="font-medium text-slate-700">{{ $item->transaction->nama_pengaju }}</span><br>
                        No. Dokumen: <span class="font-mono text-blue-600">{{ $item->transaction->doc_number }}</span>
                      </p>
                      @if($item->keterangan)
                      <div class="text-[10px] bg-slate-50 p-2 rounded text-slate-500 italic">
                        "{{ $item->keterangan }}"
                      </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-sm text-slate-500 py-4 italic">Belum ada riwayat transaksi / pergerakan aset untuk pencarian ini.</div>
            @endif
          </div>
        </div>

      </div>
      @endif
    </div>
</x-layout>

