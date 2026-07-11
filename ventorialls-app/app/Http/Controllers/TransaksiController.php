<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Inventory;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TransaksiController extends Controller
{
    // ─── Daftar kategori aset yang valid ─────────────────────────────────────
    private const KATEGORI_LIST = [
        'laptop', 'charger', 'mouse', 'lan_extender',
        'headset', 'hp_root', 'audio_jack',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // SERAH TERIMA (dari Dashboard Admin)
    // ─────────────────────────────────────────────────────────────────────────
    public function storeSerahTerima(Request $request)
    {
        $request->validate([
            'karyawan.nama'       => ['required', 'string', 'max:255'],
            'karyawan.no_wa'      => ['required', 'string', 'max:20'],
            'karyawan.department' => ['required', 'string', 'max:100'],
        ]);

        $kData = $request->karyawan;

        // Data ekstra karyawan disimpan sementara di catatan_admin sebagai JSON
        $extraData = [
            'nama_tl'      => $kData['nama_tl'] ?? null,
            'no_ktp'       => $kData['no_ktp'] ?? null,
            'alamat_ktp'   => $kData['alamat_ktp'] ?? null,
            'domisili'     => $kData['domisili'] ?? null,
            'ruangan'      => $kData['ruangan'] ?? null,
        ];

        $ticket = Ticket::create([
            'ticket_code' => Ticket::generateCode('serah_terima'),
            'type'        => 'serah_terima',
            'status'      => 'menunggu_validasi',
            'created_by'  => auth()->id(),
            'filled_at'   => now(),
        ]);

        $transaction = Transaction::create([
            'ticket_id'    => $ticket->id,
            'doc_number'   => null,
            'type'         => 'serah_terima',
            'nama_pengaju' => $kData['nama'],
            'department'   => $kData['department'],
            'no_wa'        => $kData['no_wa'],
            'status'       => 'menunggu_validasi',
            'validated_by' => null,
            'validated_at' => null,
            'catatan_admin'=> json_encode($extraData),
        ]);

        // Simpan setiap item berdasarkan kategori
        $this->saveItemsFromCategories($request, $transaction->id);

        // Jangan update Inventory di sini, update dilakukan di menu Validasi
        // $this->updateInventoryOwnership($transaction, $user);

        ActivityLog::record(
            'created', 'Transaction', $transaction->id,
            "Admin membuat draft Serah Terima (Tiket: {$ticket->ticket_code}) untuk karyawan {$kData['nama']}.",
            ['ticket_code' => $ticket->ticket_code, 'karyawan' => $kData['nama']]
        );

        return back()->with('success', "Draft Serah Terima (Tiket: {$ticket->ticket_code}) berhasil disimpan dan masuk antrean Validasi!");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PEMINJAMAN (dari Dashboard Admin)
    // ─────────────────────────────────────────────────────────────────────────
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'karyawan_id'   => ['required', 'exists:users,id'],
            'borrow_type'   => ['required', 'in:dalam,luar'],
        ]);

        $user = User::findOrFail($request->karyawan_id);

        $ticket = Ticket::create([
            'ticket_code' => Ticket::generateCode('peminjaman'),
            'type'        => 'peminjaman',
            'borrow_type' => $request->borrow_type,
            'status'      => 'menunggu_validasi',
            'created_by'  => auth()->id(),
            'filled_at'   => now(),
        ]);

        $transaction = Transaction::create([
            'ticket_id'    => $ticket->id,
            'doc_number'   => null,
            'type'         => 'peminjaman',
            'borrow_type'  => $request->borrow_type,
            'nama_pengaju' => $user->name,
            'department'   => $user->department,
            'no_wa'        => $user->kontak,
            'status'       => 'menunggu_validasi',
            'validated_by' => null,
            'validated_at' => null,
            'spv_name'     => $request->borrow_type === 'luar' ? $request->spv_name : null,
            'hrd_name'     => $request->borrow_type === 'luar' ? $request->hrd_name : null,
        ]);

        if ($request->borrow_type === 'luar') {
            $request->validate([
                'spv_name' => ['required', 'string'],
                'hrd_name' => ['required', 'string'],
                'luar_items' => ['required', 'array', 'min:1'],
            ]);
            
            foreach ($request->luar_items as $sn) {
                $inv = \App\Models\Inventory::where('sn', $sn)->first();
                if ($inv) {
                    \App\Models\TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'kategori'       => $inv->jenis,
                        'no_aset'        => $sn,
                        'keterangan'     => $inv->keterangan,
                        'foto_path'      => '',
                    ]);
                }
            }
        } else {
            $this->saveItemsFromCategories($request, $transaction->id);
        }
        // $this->updateInventoryOwnership($transaction, $user);

        ActivityLog::record(
            'created', 'Transaction', $transaction->id,
            "Admin mencatat draft Peminjaman (Tiket: {$ticket->ticket_code}) — {$request->borrow_type} — untuk {$user->name}.",
            ['ticket_code' => $ticket->ticket_code, 'borrow_type' => $request->borrow_type, 'karyawan' => $user->name]
        );

        return back()->with('success', "Draft Peminjaman (Tiket: {$ticket->ticket_code}) berhasil disimpan dan masuk antrean Validasi!");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PENUKARAN (dari Dashboard Admin)
    // ─────────────────────────────────────────────────────────────────────────
    public function storePenukaran(Request $request)
    {
        $request->validate([
            'karyawan_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($request->karyawan_id);

        $ticket = Ticket::create([
            'ticket_code' => Ticket::generateCode('penukaran'),
            'type'        => 'penukaran',
            'status'      => 'menunggu_validasi',
            'created_by'  => auth()->id(),
            'filled_at'   => now(),
        ]);

        $transaction = Transaction::create([
            'ticket_id'    => $ticket->id,
            'doc_number'   => null,
            'type'         => 'penukaran',
            'nama_pengaju' => $user->name,
            'department'   => $user->department,
            'no_wa'        => $user->kontak,
            'status'       => 'menunggu_validasi',
            'validated_by' => null,
            'validated_at' => null,
        ]);

        $this->saveItemsFromCategoriesPenukaran($request, $transaction->id);
        // $this->updateInventoryOwnershipPenukaran($transaction, $user);

        ActivityLog::record(
            'created', 'Transaction', $transaction->id,
            "Admin mencatat draft Penukaran (Tiket: {$ticket->ticket_code}) untuk {$user->name}.",
            ['ticket_code' => $ticket->ticket_code, 'karyawan' => $user->name]
        );

        return back()->with('success', "Draft Penukaran (Tiket: {$ticket->ticket_code}) berhasil disimpan dan masuk antrean Validasi!");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PENGEMBALIAN (Peminjaman External)
    // ─────────────────────────────────────────────────────────────────────────
    public function storePengembalian(Request $request)
    {
        $request->validate([
            // Since we submit from the new Retur Modal, we need to pass peminjam
            'transaction_id' => ['required'], // this is now the user ID / uniqid, but we'll use a hidden field for peminjam
            'peminjam'       => ['required', 'string'],
            'catatan'        => ['nullable', 'string'],
        ]);

        $returnedItems = $request->input('items', []);
        if (empty($returnedItems)) {
            return back()->with('error', 'Tidak ada barang yang diretur.');
        }

        $user = User::where('name', $request->peminjam)->first();

        // Buat Ticket
        $ticket = Ticket::create([
            'ticket_code' => Ticket::generateCode('pengembalian'),
            'type'        => 'pengembalian',
            'status'      => 'disetujui',
            'created_by'  => auth()->id(),
            'filled_at'   => now(),
        ]);

        // Buat Transaction
        $transaction = Transaction::create([
            'ticket_id'    => $ticket->id,
            'doc_number'   => Transaction::generateDocNumber('pengembalian'),
            'type'         => 'pengembalian',
            'nama_pengaju' => $request->peminjam,
            'department'   => $user ? $user->department : '-',
            'no_wa'        => $user ? $user->kontak : '-',
            'status'       => 'dikembalikan', // Langsung selesai
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'catatan_admin'=> $request->catatan,
        ]);

        // Update Inventory & Simpan Item
        foreach ($returnedItems as $sn => $data) {
            $inv = Inventory::where('sn', $sn)->first();
            if ($inv) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'kategori'       => $inv->jenis,
                    'no_aset'        => $sn,
                    'keterangan'     => $inv->keterangan,
                    'foto_path'      => '',
                ]);

                $tujuan = $data['tujuan'] ?? 'it';

                if ($tujuan === 'kantor') {
                    // Pindah dari pinjam luar ke pinjam dalam — tetap aktif, reset hak bawa pulang
                    $inv->update([
                        'hak_bawa_pulang' => false,
                        'kondisi'         => $data['kondisi'] ?? 'Baik',
                    ]);
                } else {
                    // Kembali sepenuhnya ke IT
                    $inv->update([
                        'pengguna'        => null,
                        'kontak'          => null,
                        'department'      => null,
                        'team_leader'     => null,
                        'spv_name'        => null,
                        'hrd_name'        => null,
                        'lokasi'          => 'Ruangan IT',
                        'status'          => 'Disimpan',
                        'hak_bawa_pulang' => false,
                        'kondisi'         => $data['kondisi'] ?? 'Baik',
                    ]);
                }
            }
        }

        ActivityLog::record(
            'returned', 'Transaction', $transaction->id,
            "Pengembalian barang dari {$request->peminjam} berhasil diproses.",
            ['transaction_id' => $transaction->id]
        );

        return back()->with('success', "Barang dari {$request->peminjam} berhasil diretur.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER PRIVATE
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Simpan item-item transaksi dari format kategori (untuk Serah Terima & Peminjaman).
     * Format input: items[laptop][no_aset], items[charger][foto], dst.
     */
    private function saveItemsFromCategories(Request $request, int $transactionId): void
    {
        $items = $request->input('items', []);
        $files = $request->file('items', []);

        foreach (self::KATEGORI_LIST as $kategori) {
            $itemData = $items[$kategori] ?? [];
            $noAset   = trim($itemData['no_aset'] ?? '');

            // Lewati jika kosong
            if (empty($noAset)) {
                continue;
            }

            $fotoPath = null;
            if (isset($files[$kategori]['foto'])) {
                $fotoPath = $files[$kategori]['foto']->store('bukti_transaksi', 'public');
            }

            TransactionItem::create([
                'transaction_id' => $transactionId,
                'kategori'       => $kategori,
                'no_aset'        => strtoupper($noAset),
                'keterangan'     => $itemData['keterangan'] ?? null,
                'sn_lama'        => null,
                'foto_path'      => $fotoPath ?? '',
            ]);
        }
    }

    /**
     * Simpan item-item transaksi Penukaran (ada sn_lama & no_aset baru).
     */
    private function saveItemsFromCategoriesPenukaran(Request $request, int $transactionId): void
    {
        $items = $request->input('items', []);
        $files = $request->file('items', []);

        foreach ($items as $key => $itemData) {
            $kategori = $itemData['kategori'] ?? (in_array($key, self::KATEGORI_LIST) ? $key : 'Unknown');
            $noAset   = trim($itemData['no_aset'] ?? '');
            $snLama   = trim($itemData['sn_lama'] ?? '');

            // Lewati jika tidak ada aset lama maupun baru
            if (empty($noAset) && empty($snLama)) {
                continue;
            }

            $fotoPath = null;
            if (isset($files[$key]['foto'])) {
                $fotoPath = $files[$key]['foto']->store('bukti_transaksi', 'public');
            }

            TransactionItem::create([
                'transaction_id' => $transactionId,
                'kategori'       => $kategori,
                'no_aset'        => strtoupper($noAset),
                'sn_lama'        => strtoupper($snLama),
                'alasan_penukaran' => $itemData['alasan_penukaran'] ?? null,
                'penjelasan_kerusakan' => $itemData['penjelasan_kerusakan'] ?? null,
                'keterangan'     => $itemData['keterangan'] ?? null,
                'foto_path'      => $fotoPath ?? '',
            ]);
        }
    }

    /**
     * Setelah transaksi Serah Terima / Peminjaman disimpan, update kepemilikan
     * di tabel inventories untuk aset yang nomor asetnya cocok.
     */
    private function updateInventoryOwnership(Transaction $transaction, User $user): void
    {
        foreach ($transaction->items as $item) {
            if (empty($item->no_aset)) continue;

            Inventory::where('sn', $item->no_aset)->update([
                'pengguna'       => $user->name,
                'kontak'         => $user->kontak,
                'department'     => $user->department,
                'team_leader'    => $user->nama_tl,
                'tanggal_signin' => now()->toDateString(),
                'status'         => 'Aktif',
            ]);
        }
    }

    /**
     * Untuk Penukaran: aset lama kembali ke gudang, aset baru ke karyawan.
     */
    private function updateInventoryOwnershipPenukaran(Transaction $transaction, User $user): void
    {
        foreach ($transaction->items as $item) {
            // Aset lama → kembalikan ke gudang
            if (!empty($item->sn_lama)) {
                Inventory::where('sn', $item->sn_lama)->update([
                    'pengguna'   => null,
                    'kontak'     => null,
                    'department' => null,
                    'status'     => 'Disimpan',
                    'kondisi'    => 'Rusak',
                ]);
            }
            // Aset baru → assign ke karyawan
            if (!empty($item->no_aset)) {
                Inventory::where('sn', $item->no_aset)->update([
                    'pengguna'       => $user->name,
                    'kontak'         => $user->kontak,
                    'department'     => $user->department,
                    'team_leader'    => $user->nama_tl,
                    'tanggal_signin' => now()->toDateString(),
                    'status'         => 'Aktif',
                ]);
            }
        }
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->pin !== '447747') {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }

        $ids = $request->ids ?? [];
        if (count($ids) > 0) {
            $transactions = Transaction::whereIn('id', $ids)->get();
            foreach ($transactions as $tx) {
                ActivityLog::record(
                    'deleted', 'Transaction', $tx->id,
                    "Admin menghapus massal transaksi: {$tx->doc_number}",
                    ['doc_number' => $tx->doc_number]
                );
                $tx->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    public function print(Transaction $transaction)
    {
        $transaction->load('items');
        return view('admin.print.transaksi', compact('transaction'));
    }

    public function printMptb(\App\Models\User $user)
    {
        $items = \App\Models\Inventory::where('pengguna', $user->name)
            ->where('hak_bawa_pulang', true)
            ->get();
            
        $latestTx = \App\Models\Transaction::where('nama_pengaju', $user->name)
            ->where('type', 'pinjam_eksternal')
            ->latest()
            ->first();
            
        return view('admin.print.hak-bawa-pulang', compact('user', 'items', 'latestTx'));
    }
}
