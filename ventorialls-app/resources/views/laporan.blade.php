<x-layout active="laporan" headerTitle="Pelacakan Histori Barang">
    <div x-data="{ searched: false }">
      <!-- Search Bar -->
      <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100 mb-6 max-w-3xl">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Cari Riwayat Inventaris</h3>
        <form @submit.prevent="searched = true" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
          <div class="relative flex-1">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            <input type="text" placeholder="Masukkan No. Aset (Cth: UP-LAP-001)" class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] outline-none font-mono text-sm uppercase">
          </div>
          <button type="submit" class="bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center justify-center shadow-soft shadow-[#1d4ed8]/30">
            Cari
          </button>
        </form>
      </div>

      <!-- Result Area -->
      <div x-show="searched" class="grid grid-cols-1 lg:grid-cols-3 gap-6" style="display: none;">
        
        <!-- Asset Info -->
        <div class="col-span-1 lg:col-span-1">
          <div class="bg-white rounded-2xl shadow-soft p-5 border border-slate-100 lg:sticky lg:top-8">
            <div class="w-12 h-12 bg-blue-50 text-[#1d4ed8] rounded-xl flex items-center justify-center mb-4">
              <i data-lucide="laptop" class="w-6 h-6"></i>
            </div>
            
            <h4 class="text-base font-bold text-slate-800 mb-1">Lenovo Thinkpad T14</h4>
            <p class="font-mono text-slate-500 text-xs mb-4">UP-LAP-001</p>
            
            <div class="space-y-3 pt-4 border-t border-slate-100">
              <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Status Terakhir</p>
                <div class="flex items-center mt-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                  <span class="text-slate-700 font-medium text-xs">Aktif Digunakan</span>
                </div>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Pengguna Saat Ini</p>
                <p class="text-slate-700 font-medium text-xs mt-1">Gita Novia Ashari (Agent)</p>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Tanggal Masuk</p>
                <p class="text-slate-700 font-medium text-xs mt-1">20 Juni 2020</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Timeline -->
        <div class="col-span-1 lg:col-span-2">
          <div class="bg-white rounded-2xl shadow-soft p-5 lg:p-6 border border-slate-100">
            <h3 class="text-sm font-bold text-slate-700 mb-6 border-b pb-3">Riwayat Pergerakan Barang (Timeline)</h3>
            
            <div class="relative border-l-2 border-slate-100 ml-3 space-y-10">
              
              <!-- Item 1 -->
              <div class="relative">
                <div class="absolute -left-[33px] bg-white p-1">
                  <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center border-4 border-white shadow-sm">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                  </div>
                </div>
                <div class="pl-12">
                  <span class="text-xs font-bold text-[#1d4ed8] uppercase tracking-wider mb-1 block">22 Juni 2026 - 09:00</span>
                  <h4 class="text-base font-bold text-slate-800">Serah Terima (Onboarding)</h4>
                  <p class="text-sm text-slate-500 mt-1">Barang diserahkan kepada **Gita Novia Ashari**.</p>
                  <a href="#" class="inline-flex items-center text-xs text-[#1d4ed8] mt-2 hover:underline"><i data-lucide="file-text" class="w-3 h-3 mr-1"></i> Lihat Dokumen BAST</a>
                </div>
              </div>

              <!-- Item 2 -->
              <div class="relative">
                <div class="absolute -left-[33px] bg-white p-1">
                  <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center border-4 border-white shadow-sm">
                    <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                  </div>
                </div>
                <div class="pl-12">
                  <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">21 Juni 2026 - 15:30</span>
                  <h4 class="text-base font-bold text-slate-800">Validasi Fisik</h4>
                  <p class="text-sm text-slate-500 mt-1">Barang divalidasi oleh Admin IT dalam kondisi Baik di Ruang Kerja.</p>
                </div>
              </div>

              <!-- Item 3 -->
              <div class="relative">
                <div class="absolute -left-[33px] bg-white p-1">
                  <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-full flex items-center justify-center border-4 border-white shadow-sm">
                    <i data-lucide="package-plus" class="w-4 h-4"></i>
                  </div>
                </div>
                <div class="pl-12">
                  <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">20 Juni 2026 - 10:00</span>
                  <h4 class="text-base font-bold text-slate-800">Barang Masuk (Registrasi Awal)</h4>
                  <p class="text-sm text-slate-500 mt-1">Barang diterima dari Vendor MPTB dan diregistrasi ke sistem oleh Super Admin.</p>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
</x-layout>

