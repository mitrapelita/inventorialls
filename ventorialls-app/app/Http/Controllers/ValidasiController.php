<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\User;
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

                Inventory::where('sn', $item->no_aset)->update($updateData);
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
}
