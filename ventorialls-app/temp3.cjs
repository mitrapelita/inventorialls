const fs = require('fs');

const modalHtml = `
        <!-- Modal Form Input Data & Transaksi Cepat -->
        <div x-show="openAddInventoryModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
             style="display: none;" x-cloak>
            <div @click.away="openAddInventoryModal = false" 
                 class="bg-white rounded-2xl shadow-xl w-full max-w-4xl flex flex-col h-[95vh] overflow-hidden">
                
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-slate-800">Registrasi Cepat Karyawan & Aset</h3>
                    <button @click="openAddInventoryModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto flex-1">
                    <form :action="formAction" method="POST" class="space-y-6 flex flex-col h-full">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        <div class="flex-1 space-y-6">

                        <!-- Langkah 1: Cari Nama Karyawan -->
                        <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                            <h4 class="text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-2">1. Pencarian Karyawan</h4>
                            <p class="text-xs text-slate-500 mb-4">Cek apakah nama karyawan sudah terdaftar di sistem. Jika ada, data diri dan daftar barang bawaannya akan otomatis terisi.</p>
                            <div class="flex items-center space-x-2 max-w-md">
                                <input type="text" name="pengguna" x-model="formData.pengguna" @keydown.enter.prevent="searchKaryawanAndAssets()" placeholder="Ketik nama karyawan lalu tekan Enter..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-[#1d4ed8]/20 focus:border-[#1d4ed8] font-medium text-slate-700">
                                <button type="button" @click="searchKaryawanAndAssets()" class="px-4 py-2.5 bg-[#1d4ed8] text-white rounded-xl border border-transparent hover:bg-blue-800 flex-shrink-0 transition-colors shadow-sm" :disabled="isSearching" title="Cari Data Karyawan">
                                    <i data-lucide="search" class="w-5 h-5" x-show="!isSearching"></i>
                                    <i data-lucide="loader-2" class="w-5 h-5 animate-spin" x-show="isSearching" style="display:none"></i>
                                </button>
                            </div>

                            <!-- Hasil Pencarian: Barang Bawaan (Jika Ditemukan) -->
                            <div x-show="userFound && userAssets.length > 0" x-transition class="mt-4 pt-4 border-t border-blue-100" style="display: none;">
                                <h5 class="text-xs font-bold text-slate-700 mb-2">Barang yang Sedang Digunakan Karyawan Ini:</h5>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="asset in userAssets" :key="asset.id">
                                        <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs shadow-sm">
                                            <i data-lucide="laptop" class="w-3.5 h-3.5 text-slate-400 mr-2"></i>
                                            <span class="font-semibold text-slate-700 mr-1" x-text="asset.jenis"></span>
                                            <span class="text-slate-500 mr-1" x-text="asset.merk"></span>
                                            <span class="text-slate-400 font-mono" x-text="'(' + asset.sn + ')'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Langkah 2: Lengkapi Data Diri (Ditampilkan selalu setelah pencarian) -->
                        <div x-show="searchDone" x-transition style="display: none;">
                            <h4 class="text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-4 border-b pb-2">2. Lengkapi Data Diri Karyawan</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kontak Karyawan (WA)</label>
                                    <input type="text" name="kontak" x-model="formData.kontak" placeholder="0812xxxx" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Department</label>
                                    <select name="department" x-model="formData.department" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Agent">Agent</option>
                                        <option value="IT">IT</option>
                                        <option value="HR">HR</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Translator">Translator</option>
                                        <option value="TL">TL</option>
                                        <option value="QC">QC</option>
                                        <option value="SPV">SPV</option>
                                        <option value="Vendor">Vendor</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Langkah 3: Detail Barang Baru -->
                        <div x-show="searchDone" x-transition style="display: none;">
                            <h4 class="text-sm font-bold text-[#1d4ed8] uppercase tracking-wider mb-4 border-b pb-2">3. Registrasi Barang Baru</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Barang</label>
                                    <select name="jenis" x-model="formData.jenis" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Laptop">Laptop</option>
                                        <option value="LAN Extender">LAN Extender</option>
                                        <option value="Charger">Charger</option>
                                        <option value="Mouse">Mouse</option>
                                        <option value="Headset">Headset</option>
                                        <option value="HP Root">HP Root</option>
                                        <option value="Audio Jack">Audio Jack</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Merk / Tipe</label>
                                    <input type="text" name="merk" x-model="formData.merk" placeholder="Lenovo Thinkpad" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. Aset (Baru)</label>
                                    <input type="text" name="sn" x-model="formData.sn" required class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none font-mono">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi Barang</label>
                                    <select name="kondisi" x-model="formData.kondisi" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak">Rusak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status Ketersediaan</label>
                                    <select name="status" x-model="formData.status" class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 outline-none">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Disimpan">Disimpan</option>
                                        <option value="Return Vendor">Return Vendor</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        </div> <!-- end content space -->
                        
                        <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3 mt-auto" x-show="searchDone" x-transition>
                            <button type="button" @click="openAddInventoryModal = false" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl font-medium text-white bg-gradient-to-br from-[#1d4ed8] to-[#3b82f6] hover:bg-[#1e40af] shadow-soft transition-all flex items-center">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                <span>Simpan & Buat Transaksi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
`;

const alpineProperties = `
          pendingForm: null,
          openAddInventoryModal: false,
          formAction: '/workspaceinventory/validasi/quick-register',
          formMethod: 'POST',
          searchDone: false,
          userFound: false,
          userAssets: [],
          defaultData: {
              jenis: 'Laptop', merk: '', sn: '',
              pengguna: '', kontak: '', department: 'Agent',
              kondisi: 'Baik', status: 'Aktif'
          },
          formData: {},
          isSearching: false,
`;

const alpineMethods = `
          async searchKaryawanAndAssets() {
              if (!this.formData.pengguna) return;
              this.isSearching = true;
              this.searchDone = false;
              this.userFound = false;
              this.userAssets = [];
              try {
                  const response = await fetch('/workspaceinventory/api/search/karyawan?q=' + encodeURIComponent(this.formData.pengguna));
                  const data = await response.json();
                  if (data.found && data.data) {
                      this.userFound = true;
                      this.formData.pengguna = data.data.nama;
                      this.formData.department = data.data.department;
                      this.formData.kontak = data.data.kontak;
                      if(data.data.aset_aktif) {
                          this.userAssets = data.data.aset_aktif;
                      }
                  } else {
                      // Karyawan tidak ditemukan, biarkan input manual
                      this.userFound = false;
                      this.formData.department = 'Agent';
                      this.formData.kontak = '';
                  }
                  this.searchDone = true;
                  this.$nextTick(() => { if(window.lucide) window.lucide.createIcons(); });
              } catch (e) {
                  console.error('Error fetching data', e);
              } finally {
                  this.isSearching = false;
              }
          },
          openAddModal() {
              this.searchDone = false;
              this.userFound = false;
              this.userAssets = [];
              this.formAction = '/workspaceinventory/validasi/quick-register';
              this.formMethod = 'POST';
              this.formData = JSON.parse(JSON.stringify(this.defaultData));
              if(this.searchSn) {
                  this.formData.sn = this.searchSn.trim().toUpperCase();
              }
              this.openAddInventoryModal = true;
              this.$nextTick(() => { if(window.lucide) window.lucide.createIcons(); });
          },
          submitPending() {`;


let validasi = fs.readFileSync('resources/views/admin/validasi.blade.php', 'utf8');

// Replace the modal HTML
const startMarker = '<!-- Modal Form Input Data (14 Fields) -->';
const endMarker = '<!-- CONFIRMATION MODAL -->';
if(validasi.includes(startMarker)) {
    let parts = validasi.split(startMarker);
    let rightParts = parts[1].split(endMarker);
    
    validasi = parts[0] + modalHtml + '\n    ' + endMarker + rightParts[1];
}

// Ensure the startMarker is replaced correctly by the new modalHtml which contains <!-- Modal Form Input Data & Transaksi Cepat -->
// Because I omitted startMarker from the concatenation, the new modal takes its place.

// Replace Alpine JS properties
const alpinePropsStart = '          pendingForm: null,';
const alpinePropsEnd = '          async searchKaryawan() {'; // We previously injected this
let propsParts = validasi.split(alpinePropsStart);
let propsRightParts = propsParts[1].split(alpinePropsEnd);
validasi = propsParts[0] + alpineProperties + '\n' + alpinePropsEnd + propsRightParts[1];

// Replace Alpine JS methods (we need to replace searchKaryawan and openAddModal)
const methodsStart = '          async searchKaryawan() {';
const methodsEnd = '          submitPending() {';
let methParts = validasi.split(methodsStart);
let methRightParts = methParts[1].split(methodsEnd);
// note methRightParts[1] has the rest of submitPending() {
validasi = methParts[0] + alpineMethods + methRightParts[1];

fs.writeFileSync('resources/views/admin/validasi.blade.php', validasi);
console.log('Update UI script done');
