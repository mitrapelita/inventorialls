<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ValidasiController extends Controller
{
    /**
     * Approve transaksi — generate doc_number, update Inventory, dan catat log.
     */
    public function approve(Request $request, Transaction $transaction)
    {
        $transaction->load('items');

        // Proses perubahan data item (jika ada) dari Admin
        if ($request->has('items') && is_array($request->input('items'))) {
            foreach ($request->input('items') as $itemId => $itemData) {
                $item = $transaction->items->where('id', $itemId)->first();
                if ($item) {
                    $changes = [];
                    if (isset($itemData['no_aset']) && $item->no_aset !== $itemData['no_aset']) {
                        $changes[] = "No Aset diubah dari {$item->no_aset} menjadi {$itemData['no_aset']}";
                        $item->no_aset = $itemData['no_aset'];
                    }
                    if (isset($itemData['sn_lama']) && $item->sn_lama !== $itemData['sn_lama']) {
                        $changes[] = "SN Lama diubah dari {$item->sn_lama} menjadi {$itemData['sn_lama']}";
                        $item->sn_lama = $itemData['sn_lama'];
                    }
                    
                    if (!empty($changes)) {
                        $item->keterangan = ($item->keterangan ? $item->keterangan . ' | ' : '') . '[Admin Edit: ' . implode(', ', $changes) . ']';
                        $item->save();
                    }
                }
            }
            // Reload items untuk memastikan data terbaru digunakan saat update Inventory
            $transaction->load('items');
        }

        // Cari data user karyawan dari nama pengaju
        $karyawan = User::where('name', $transaction->nama_pengaju)
            ->where('role', 'karyawan')
            ->first();

        // Jika ini transaksi serah terima (registrasi karyawan), kita buat/update data Karyawannya
        if ($transaction->type === 'serah_terima' && !empty($transaction->catatan_admin)) {
            $extra = json_decode($transaction->catatan_admin, true);
            if (is_array($extra)) {
                if (!$karyawan) {
                    $karyawan = User::create([
                        'name'         => $transaction->nama_pengaju,
                        'role'         => 'karyawan',
                        'email'        => strtolower(str_replace(' ', '.', $transaction->nama_pengaju)) . rand(10, 99) . '@mptb.co',
                        'password'     => Hash::make('karyawan123'),
                        'department'   => $transaction->department,
                        'kontak'       => $transaction->no_wa,
                        'nama_tl'      => $extra['nama_tl'] ?? null,
                        'no_ktp'       => $extra['no_ktp'] ?? null,
                        'alamat_ktp'   => $extra['alamat_ktp'] ?? null,
                        'domisili'     => $extra['domisili'] ?? null,
                        'ruangan'      => $extra['ruangan'] ?? null,
                        'status_kerja' => 'Aktif',
                    ]);
                } else {
                    $karyawan->update([
                        'department' => $transaction->department,
                        'kontak'     => $transaction->no_wa ?? $karyawan->kontak,
                        'nama_tl'    => $extra['nama_tl'] ?? $karyawan->nama_tl,
                        'no_ktp'     => $extra['no_ktp'] ?? $karyawan->no_ktp,
                        'alamat_ktp' => $extra['alamat_ktp'] ?? $karyawan->alamat_ktp,
                        'domisili'   => $extra['domisili'] ?? $karyawan->domisili,
                        'ruangan'    => $extra['ruangan'] ?? $karyawan->ruangan,
                    ]);
                }
                
                // Bersihkan catatan_admin agar rapi (atau bisa dibiarkan saja)
                $transaction->catatan_admin = null;
            }
        } elseif ($transaction->type === 'peminjaman' && $transaction->borrow_type === 'luar' && !empty($transaction->catatan_admin)) {
            $extra = json_decode($transaction->catatan_admin, true);
            if (is_array($extra) && $karyawan) {
                $karyawan->update([
                    'nama_tl'    => $extra['nama_tl'] ?? $karyawan->nama_tl,
                    'no_ktp'     => $extra['no_ktp'] ?? $karyawan->no_ktp,
                    'alamat_ktp' => $extra['alamat_ktp'] ?? $karyawan->alamat_ktp,
                    'domisili'   => $extra['domisili'] ?? $karyawan->domisili,
                    'ruangan'    => $extra['ruangan'] ?? $karyawan->ruangan,
                ]);
                $transaction->catatan_admin = null;
            }
        }

        $docNumber = Transaction::generateDocNumber($transaction->type);

        $transaction->update([
            'status'       => 'disetujui',
            'doc_number'   => $docNumber,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        // Update status tiket induk
        $transaction->ticket->update(['status' => 'selesai']);

        // ─ Update kepemilikan Inventory ────────────────────────────────────
        if (in_array($transaction->type, ['serah_terima', 'peminjaman'])) {
            foreach ($transaction->items as $item) {
                if (empty($item->no_aset)) continue;
                $updateData = [
                    'pengguna'       => $transaction->nama_pengaju,
                    'kontak'         => $transaction->no_wa,
                    'department'     => $transaction->department,
                    'team_leader'    => $karyawan?->nama_tl,
                    'tanggal_signin' => now()->toDateString(),
                    'status'         => 'Aktif',
                ];

                if ($transaction->type === 'peminjaman' && $transaction->borrow_type === 'luar') {
                    $updateData['hak_bawa_pulang'] = true;
                }

                // Cek apakah item sudah ada di Master Data (Inventory)
                $inv = Inventory::where('sn', $item->no_aset)->first();
                if (!$inv) {
                    // Ekstrak merk dari keterangan jika ada (misal: "Merk: Lenovo")
                    $merk = 'Tidak Diketahui';
                    if (preg_match('/Merk:\s*(.*)/', $item->keterangan ?? '', $matches)) {
                        $merk = trim($matches[1]);
                    }

                    // Buat aset baru di Master Data karena aset tidak ditemukan
                    Inventory::create([
                        'jenis'          => $item->kategori,
                        'merk'           => $merk,
                        'sn'             => $item->no_aset,
                        'kondisi'        => 'Baik',
                        'status'         => 'Aktif',
                        'kepemilikan'    => 'PTMPTB',
                        'pengguna'       => $transaction->nama_pengaju,
                        'kontak'         => $transaction->no_wa,
                        'department'     => $transaction->department,
                        'team_leader'    => $karyawan?->nama_tl,
                        'tanggal_masuk'  => now()->toDateString(),
                        'tanggal_signin' => now()->toDateString(),
                        'lokasi'         => 'Di MPTB',
                        'hak_bawa_pulang'=> ($transaction->type === 'peminjaman' && $transaction->borrow_type === 'luar')
                    ]);
                } else {
                    // Update kepemilikan jika aset sudah ada
                    $inv->update($updateData);
                }
            }
        }

        if ($transaction->type === 'penukaran') {
            foreach ($transaction->items as $item) {
                // Aset lama → kembalikan ke gudang, kondisi rusak
                if (!empty($item->sn_lama)) {
                    $updateOldData = [
                        'pengguna'   => null,
                        'kontak'     => null,
                        'department' => null,
                        'status'     => 'Disimpan',
                        'lokasi'     => 'Ruangan IT',
                        'kondisi'    => ($item->alasan_penukaran === 'rusak') ? 'Rusak' : 'Baik',
                    ];

                    if ($item->alasan_penukaran === 'rusak' && !empty($item->penjelasan_kerusakan)) {
                        $updateOldData['keterangan'] = $item->penjelasan_kerusakan;
                    }

                    Inventory::where('sn', $item->sn_lama)->update($updateOldData);
                }
                // Aset baru → diberikan ke karyawan
                if (!empty($item->no_aset)) {
                    Inventory::where('sn', $item->no_aset)->update([
                        'pengguna'       => $transaction->nama_pengaju,
                        'kontak'         => $transaction->no_wa,
                        'department'     => $transaction->department,
                        'team_leader'    => $karyawan?->nama_tl,
                        'tanggal_signin' => now()->toDateString(),
                        'status'         => 'Aktif',
                        'lokasi'         => 'Di MPTB',
                    ]);
                }
            }
        }

        ActivityLog::record(
            'approved', 'Transaction', $transaction->id,
            "Admin menyetujui transaksi {$docNumber} dari {$transaction->nama_pengaju}.",
            ['doc_number' => $docNumber, 'type' => $transaction->type, 'karyawan' => $transaction->nama_pengaju]
        );

        return back()->with('success', "Transaksi dari {$transaction->nama_pengaju} telah disetujui dan data aset diperbarui.");
    }

    /**
     * Tolak transaksi — simpan catatan, catat log.
     */
    public function reject(Request $request, Transaction $transaction)
    {
        $request->validate([
            'catatan_admin' => ['required', 'string'],
        ]);

        $transaction->update([
            'status'        => 'ditolak',
            'validated_by'  => Auth::id(),
            'validated_at'  => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Kembalikan status tiket agar bisa diisi ulang oleh karyawan
        $transaction->ticket->update(['status' => 'menunggu_diisi', 'filled_at' => null]);

        ActivityLog::record(
            'rejected', 'Transaction', $transaction->id,
            "Admin menolak transaksi dari {$transaction->nama_pengaju}. Alasan: {$request->catatan_admin}",
            ['type' => $transaction->type, 'karyawan' => $transaction->nama_pengaju]
        );

        return back()->with('success', "Transaksi dari {$transaction->nama_pengaju} telah ditolak.");
    }

    
    public function quickRegister(Request $request)
    {
        // Filter baris item yang kosong (tidak diisi merk/sn sama sekali)
        $items = $request->input('items', []);
        $filteredItems = array_filter($items, function($item) {
            return !empty($item['sn']) || !empty($item['merk']);
        });
        $request->merge(['items' => array_values($filteredItems)]);

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
            
            'items'          => ['nullable', 'array'],
            'items.*.jenis'  => ['required', 'string', 'max:100'],
            'items.*.merk'   => ['nullable', 'string', 'max:255'],
            'items.*.sn'     => ['nullable', 'string', 'max:100', 'unique:inventories,sn'],
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

        // Buat Transaksi jika ada items
        if (!empty($request->items)) {
            $transaction = Transaction::create([
                'ticket_id'      => null,
                'doc_number'     => null, // Diisi saat disetujui
                'type'           => 'serah_terima',
                'borrow_type'    => null,
                'nama_pengaju'   => $karyawan->name,
                'department'     => $karyawan->department,
                'no_wa'          => $karyawan->kontak,
                'status'         => 'menunggu_validasi',
                'catatan_admin'  => null,
            ]);

            foreach ($request->items as $itemData) {
                // Generate fallback untuk merk dan sn
                $merk = !empty($itemData['merk']) ? $itemData['merk'] : 'Tidak Diketahui';
                $sn = !empty($itemData['sn']) ? $itemData['sn'] : 'TBA-' . strtoupper(\Illuminate\Support\Str::random(6));

                // Buat Item Transaksi Saja
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'kategori'       => $itemData['jenis'],
                    'no_aset'        => $sn,
                    'keterangan'     => 'Registrasi & Peminjaman Cepat dari Tools Validasi | Merk: ' . $merk,
                ]);
            }
        }

        ActivityLog::record(
            'created', 'Transaction', $transaction->id,
            'Admin membuat Transaksi Peminjaman Cepat (' . count($request->items) . ' barang) untuk ' . $karyawan->name,
            ['type' => 'peminjaman']
        );

        return back()->with('success', count($request->items) . ' Aset baru berhasil ditambahkan dan masuk ke Pusat Validasi.');
    }

    public function deleteItem(Request $request, $id)
    {
        $item = TransactionItem::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

}