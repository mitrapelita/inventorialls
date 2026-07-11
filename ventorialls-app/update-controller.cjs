const fs = require('fs');
let content = fs.readFileSync('app/Http/Controllers/ValidasiController.php', 'utf8');

const newMethodStr = `
    public function quickRegister(Request $request)
    {
        $request->validate([
            'pengguna'       => ['required', 'string', 'max:255'],
            'kontak'         => ['nullable', 'string', 'max:20'],
            'department'     => ['nullable', 'string', 'max:100'],
            'id_karyawan'    => ['nullable', 'string', 'max:50'],
            'nama_tl'        => ['nullable', 'string', 'max:255'],
            'no_ktp'         => ['nullable', 'string', 'max:50'],
            'alamat_ktp'     => ['nullable', 'string'],
            'domisili'       => ['nullable', 'string'],
            'ruangan'        => ['nullable', 'string', 'max:255'],
            
            'items'          => ['required', 'array', 'min:1'],
            'items.*.jenis'  => ['required', 'string', 'max:100'],
            'items.*.merk'   => ['required', 'string', 'max:255'],
            'items.*.sn'     => ['required', 'string', 'max:100', 'unique:inventories,sn'],
            'items.*.kondisi'=> ['required', 'in:Baik,Rusak'],
            'items.*.status' => ['required', 'in:Aktif,Disimpan,Return Vendor'],
        ]);

        // Cek karyawan
        $karyawan = User::where('role', 'karyawan')
            ->where('name', $request->pengguna)
            ->first();

        if (!$karyawan) {
            $karyawan = User::create([
                'name'         => $request->pengguna,
                'role'         => 'karyawan',
                'email'        => strtolower(str_replace(' ', '.', $request->pengguna)) . rand(10, 99) . '@mptb.co',
                'password'     => Hash::make('karyawan123'),
                'department'   => $request->department,
                'kontak'       => $request->kontak,
                'id_karyawan'  => $request->id_karyawan,
                'nama_tl'      => $request->nama_tl,
                'no_ktp'       => $request->no_ktp,
                'alamat_ktp'   => $request->alamat_ktp,
                'domisili'     => $request->domisili,
                'ruangan'      => $request->ruangan,
                'status_kerja' => 'Aktif',
            ]);
        } else {
            // Update jika ada field kosong (atau update semuanya jika dikirim)
            $updates = [];
            if ($request->kontak) $updates['kontak'] = $request->kontak;
            if ($request->department) $updates['department'] = $request->department;
            if ($request->id_karyawan) $updates['id_karyawan'] = $request->id_karyawan;
            if ($request->nama_tl) $updates['nama_tl'] = $request->nama_tl;
            if ($request->no_ktp) $updates['no_ktp'] = $request->no_ktp;
            if ($request->alamat_ktp) $updates['alamat_ktp'] = $request->alamat_ktp;
            if ($request->domisili) $updates['domisili'] = $request->domisili;
            if ($request->ruangan) $updates['ruangan'] = $request->ruangan;
            
            if (!empty($updates)) {
                $karyawan->update($updates);
            }
        }

        // Buat Transaksi
        $transaction = Transaction::create([
            'ticket_id'      => null,
            'doc_number'     => null, // Diisi saat disetujui
            'type'           => 'peminjaman',
            'borrow_type'    => 'dalam',
            'nama_pengaju'   => $karyawan->name,
            'department'     => $karyawan->department,
            'no_wa'          => $karyawan->kontak,
            'status'         => 'pending',
            'catatan_admin'  => null,
        ]);

        foreach ($request->items as $itemData) {
            // Buat Aset
            $inventory = Inventory::create([
                'jenis'          => $itemData['jenis'],
                'merk'           => $itemData['merk'],
                'sn'             => $itemData['sn'],
                'kondisi'        => $itemData['kondisi'],
                'status'         => $itemData['status'],
                'kepemilikan'    => 'PTMPTB',
                'pengguna'       => $karyawan->name,
                'kontak'         => $karyawan->kontak,
                'department'     => $karyawan->department,
                'tanggal_masuk'  => now()->toDateString(),
                'tanggal_signin' => now()->toDateString(),
                'lokasi'         => 'Di MPTB'
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'jenis_barang'   => $inventory->jenis,
                'merk_tipe'      => $inventory->merk,
                'no_aset'        => $inventory->sn,
                'keterangan'     => 'Registrasi & Peminjaman Cepat dari Tools Validasi',
            ]);
        }

        ActivityLog::record(
            'created', 'Transaction', $transaction->id,
            'Admin membuat Transaksi Peminjaman Cepat (' . count($request->items) . ' barang) untuk ' . $karyawan->name,
            ['type' => 'peminjaman']
        );

        return back()->with('success', count($request->items) . ' Aset baru berhasil ditambahkan dan masuk ke Pusat Validasi.');
    }
`;

let startIndex = content.indexOf('public function quickRegister(Request $request)');
let endIndex = content.indexOf('}', content.lastIndexOf('return back()')) + 1; // End of the method body
let finalString = content.substring(0, startIndex) + newMethodStr + content.substring(endIndex);

fs.writeFileSync('app/Http/Controllers/ValidasiController.php', finalString);
console.log('Controller updated.');
