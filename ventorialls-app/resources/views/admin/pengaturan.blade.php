<x-layout title="Pengaturan Admin" active="pengaturan">
    <div class="space-y-6" x-data="adminSettings()">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Admin & Penandatangan</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data akun admin untuk login, serta daftar nama SPV dan HRD untuk keperluan cetak.</p>
            </div>
            <button @click="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm flex items-center justify-center">
                <i data-lucide="shield-plus" class="w-4 h-4 mr-2"></i> Tambah Admin
            </button>
        </div>

        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-start">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3 shrink-0 mt-0.5"></i>
            <div>
                <h3 class="font-bold text-sm">Berhasil</h3>
                <p class="text-sm mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-3 shrink-0 mt-0.5"></i>
            <div>
                <h3 class="font-bold text-sm">Gagal</h3>
                <p class="text-sm mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-start">
            <i data-lucide="alert-triangle" class="w-5 h-5 mr-3 shrink-0 mt-0.5"></i>
            <div>
                <h3 class="font-bold text-sm">Terdapat Kesalahan</h3>
                <ul class="text-sm mt-1 list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Data Admin -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-100 uppercase">
                        <tr>
                            <th class="px-6 py-4 font-medium">Nama IT (Untuk Cetak)</th>
                            <th class="px-6 py-4 font-medium">Username (ID Karyawan)</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($admins as $admin)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 shrink-0">
                                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $admin->name }}</span>
                                    @if(auth()->id() === $admin->id)
                                        <span class="ml-2 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md text-[10px] font-bold tracking-wide">ANDA</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-600">{{ $admin->id_karyawan }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-medium border border-emerald-100/50">Aktif</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button @click="confirmEdit({{ json_encode($admin) }})" 
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit Admin">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    @if(auth()->id() !== $admin->id)
                                    <button @click="confirmDelete({{ json_encode($admin) }})" 
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Hapus Admin">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-8 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Daftar SPV & HRD</h3>
            <button @click="openApproverModal('add')" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm flex items-center justify-center">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Tambah SPV/HRD
            </button>
        </div>
        <!-- Data Approvers -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-100 uppercase">
                        <tr>
                            <th class="px-6 py-4 font-medium">Nama Lengkap</th>
                            <th class="px-6 py-4 font-medium">Jabatan / Role</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($approvers as $appr)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $appr->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 {{ $appr->role === 'SPV' ? 'bg-amber-50 text-amber-600 border-amber-100/50' : 'bg-purple-50 text-purple-600 border-purple-100/50' }} rounded-lg text-xs font-medium border">
                                    {{ $appr->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button @click="confirmEditApprover({{ json_encode($appr) }})" 
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit SPV/HRD">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="confirmDeleteApprover({{ json_encode($appr) }})" 
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Hapus SPV/HRD">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah/Edit -->
        <div x-cloak x-show="isModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal()"></div>

            <!-- Modal Panel -->
            <div class="relative bg-white w-full max-w-md rounded-2xl shadow-xl flex flex-col"
                 @click.stop
                 x-show="isModalOpen"
                 x-transition:enter="transition ease-out duration-300 delay-100"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95">
                
                <form :action="formAction" method="POST">
                    @csrf
                    <!-- Dynamic Method -->
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800" x-text="mode === 'add' ? 'Tambah Admin Baru' : 'Edit Data Admin'"></h3>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-500 bg-slate-50 hover:bg-slate-100 p-2 rounded-full transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap IT <span class="text-rose-500">*</span></label>
                            <p class="text-xs text-slate-500 mb-2">Nama ini akan digunakan sebagai penanda tangan di cetak Print.</p>
                            <input type="text" name="name" x-model="formData.name" required
                                placeholder="Cth: Shady Arya"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Username / ID Karyawan <span class="text-rose-500">*</span></label>
                            <p class="text-xs text-slate-500 mb-2">Digunakan untuk login ke sistem.</p>
                            <input type="text" name="id_karyawan" x-model="formData.id_karyawan" required
                                placeholder="Cth: admin2"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Password <span x-show="mode === 'add'" class="text-rose-500">*</span></label>
                            <p class="text-xs text-slate-500 mb-2" x-text="mode === 'add' ? 'Minimal 6 karakter.' : 'Kosongkan jika tidak ingin mengubah password.'"></p>
                            <input type="password" name="password" x-model="formData.password" :required="mode === 'add'"
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 focus:bg-white transition-all">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3 rounded-b-2xl">
                        <button type="button" @click="closeModal()" 
                            class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors shadow-sm">
                            <span x-text="mode === 'add' ? 'Simpan Admin' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tambah/Edit Approver -->
        <div x-cloak x-show="isApproverModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeApproverModal()"></div>

            <!-- Modal Panel -->
            <div class="relative bg-white w-full max-w-md rounded-2xl shadow-xl flex flex-col"
                 @click.stop
                 x-show="isApproverModalOpen"
                 x-transition:enter="transition ease-out duration-300 delay-100"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-4 sm:scale-95">
                 
                <form :action="approverFormAction" method="POST">
                    @csrf
                    <template x-if="approverMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800" x-text="approverMode === 'add' ? 'Tambah SPV/HRD Baru' : 'Edit Data SPV/HRD'"></h3>
                        <button type="button" @click="closeApproverModal()" class="text-slate-400 hover:text-slate-500 bg-slate-50 hover:bg-slate-100 p-2 rounded-full transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="approverFormData.name" required
                                placeholder="Cth: Budi Santoso"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Jabatan / Role <span class="text-rose-500">*</span></label>
                            <select name="role" x-model="approverFormData.role" required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 focus:bg-white transition-all cursor-pointer">
                                <option value="" disabled>Pilih Jabatan</option>
                                <option value="SPV">Supervisor (SPV)</option>
                                <option value="HRD">HRD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3 rounded-b-2xl">
                        <button type="button" @click="closeApproverModal()" 
                            class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-medium transition-colors shadow-sm">
                            <span x-text="approverMode === 'add' ? 'Simpan' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- PIN Modal for Delete -->
        <div x-show="openPinModal" 
             class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" 
             style="display: none;">
            <div @click.away="openPinModal = false" class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-fade-in text-center p-4 md:p-6">
                <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="lock" class="w-8 h-8 text-rose-500"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Keamanan Sistem</h3>
                <p class="text-sm text-slate-500 mb-4">Masukkan 6-digit PIN untuk <span class="font-bold text-slate-700" x-text="
                    pinAction === 'delete' ? 'menghapus admin ' + (targetAdmin ? targetAdmin.name : '') : 
                    (pinAction === 'edit' ? 'mengedit admin ' + (targetAdmin ? targetAdmin.name : '') :
                    (pinAction === 'deleteApprover' ? 'menghapus SPV/HRD ' + (targetApprover ? targetApprover.name : '') : 
                    'mengedit SPV/HRD ' + (targetApprover ? targetApprover.name : '')))
                "></span>.</p>
                
                <input type="password" x-model="pinInput" placeholder="••••••" class="w-full text-center tracking-[1em] text-xl font-bold px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition-all mb-2" maxlength="6">
                
                <p x-show="pinError" x-text="pinError" class="text-rose-500 text-xs font-bold mb-4 h-4"></p>
                
                <div class="flex space-x-3 mt-4">
                    <button type="button" @click="openPinModal = false; pinInput = ''; pinError = ''" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="button" @click="processPin()" :disabled="deleteProcessing" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 transition-colors shadow-soft disabled:opacity-50 flex justify-center items-center">
                        <span x-show="!deleteProcessing">Konfirmasi</span>
                        <i x-show="deleteProcessing" data-lucide="loader-2" class="w-4 h-4 animate-spin" style="display: none;"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminSettings', () => ({
                isModalOpen: false,
                mode: 'add',
                formAction: '{{ route('pengaturan.store') }}',
                formData: {
                    id: '',
                    name: '',
                    id_karyawan: '',
                    password: ''
                },
                openPinModal: false,
                pinInput: '',
                pinError: '',
                pinAction: '', // 'edit' or 'delete' or 'editApprover' or 'deleteApprover'
                deleteProcessing: false,
                targetAdmin: null,

                isApproverModalOpen: false,
                approverMode: 'add',
                approverFormAction: '{{ route('pengaturan.approver.store') }}',
                approverFormData: {
                    id: '',
                    name: '',
                    role: ''
                },
                targetApprover: null,
                
                openModal(mode, data = null) {
                    this.mode = mode;
                    if (mode === 'edit' && data) {
                        this.formData.id = data.id;
                        this.formData.name = data.name;
                        this.formData.id_karyawan = data.id_karyawan;
                        this.formData.password = ''; // Jangan tampilkan password lama
                        this.formAction = `/workspaceinventory/pengaturan/${data.id}`;
                    } else {
                        this.formData.id = '';
                        this.formData.name = '';
                        this.formData.id_karyawan = '';
                        this.formData.password = '';
                        this.formAction = '{{ route('pengaturan.store') }}';
                    }
                    this.isModalOpen = true;
                    
                    setTimeout(() => {
                        if(window.lucide) window.lucide.createIcons();
                    }, 50);
                },
                
                closeModal() {
                    this.isModalOpen = false;
                },

                confirmEdit(admin) {
                    this.targetAdmin = admin;
                    this.pinAction = 'edit';
                    this.openPinModal = true;
                    this.pinInput = '';
                    this.pinError = '';
                },

                confirmDelete(admin) {
                    this.targetAdmin = admin;
                    this.pinAction = 'delete';
                    this.openPinModal = true;
                    this.pinInput = '';
                    this.pinError = '';
                },

                openApproverModal(mode, data = null) {
                    this.approverMode = mode;
                    if (mode === 'edit' && data) {
                        this.approverFormData.id = data.id;
                        this.approverFormData.name = data.name;
                        this.approverFormData.role = data.role;
                        this.approverFormAction = `/workspaceinventory/pengaturan/approver/${data.id}`;
                    } else {
                        this.approverFormData.id = '';
                        this.approverFormData.name = '';
                        this.approverFormData.role = '';
                        this.approverFormAction = '{{ route('pengaturan.approver.store') }}';
                    }
                    this.isApproverModalOpen = true;
                },

                closeApproverModal() {
                    this.isApproverModalOpen = false;
                },

                confirmEditApprover(approver) {
                    this.targetApprover = approver;
                    this.pinAction = 'editApprover';
                    this.openPinModal = true;
                    this.pinInput = '';
                    this.pinError = '';
                },

                confirmDeleteApprover(approver) {
                    this.targetApprover = approver;
                    this.pinAction = 'deleteApprover';
                    this.openPinModal = true;
                    this.pinInput = '';
                    this.pinError = '';
                },

                processPin() {
                    this.pinError = '';
                    if (this.pinInput !== '447747') {
                        this.pinError = 'PIN salah!';
                        return;
                    }

                    if (this.pinAction === 'edit') {
                        this.openPinModal = false;
                        this.openModal('edit', this.targetAdmin);
                        return;
                    }

                    if (this.pinAction === 'editApprover') {
                        this.openPinModal = false;
                        this.openApproverModal('edit', this.targetApprover);
                        return;
                    }

                    // For Delete Action
                    this.deleteProcessing = true;
                    
                    const url = this.pinAction === 'deleteApprover' 
                        ? '/workspaceinventory/pengaturan/approver/bulk-delete' 
                        : '/workspaceinventory/pengaturan/bulk-delete';
                        
                    const targetId = this.pinAction === 'deleteApprover' 
                        ? this.targetApprover.id 
                        : this.targetAdmin.id;

                    fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ ids: [targetId], pin: this.pinInput })
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            this.pinError = data.message;
                            this.deleteProcessing = false;
                        }
                    }).catch(e => {
                        this.pinError = 'Terjadi kesalahan sistem';
                        this.deleteProcessing = false;
                    });
                }
            }));
        });
    </script>
    @endpush
</x-layout>
