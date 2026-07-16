<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class UserTicketController extends Controller
{
    private const KATEGORI_LIST = [
        'laptop', 'charger', 'mouse', 'lan_extender',
        'headset', 'hp_root', 'audio_jack',
    ];

    /**
     * Proses submit form tiket dari karyawan (format baru: per kategori)
     */
    public function submit(Request $request, string $type)
    {
        $ticketCode = $request->input('ticket_code');

        // Cari tiket yang valid
        $ticket = Ticket::where('ticket_code', $ticketCode)
            ->where('type', str_replace('-', '_', $type))
            ->where('status', 'menunggu_diisi')
            ->firstOrFail();

        // Validasi data karyawan
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:100'],
            'ruangan' => ['required', 'string', 'max:100'],
            'no_wa' => ['required', 'string', 'max:20'],
        ]);

        // Pastikan paling sedikit satu item diisi
        $hasItem = false;

        if ($ticket->type === 'peminjaman' && $ticket->borrow_type === 'luar') {
            $request->validate([
                'spv_name' => ['required', 'string'],
                'hrd_name' => ['required', 'string'],
                'luar_items' => ['required', 'array', 'min:1'],
                'team_leader' => ['nullable', 'string', 'max:255'],
                'nik_ktp' => ['nullable', 'string', 'max:50'],
                'alamat_ktp' => ['nullable', 'string'],
                'domisili' => ['nullable', 'string'],
                'ruangan' => ['nullable', 'string', 'max:100'],
            ]);
            $hasItem = true;
        } else {
            $items = $request->input('items', []);
            foreach ($items as $key => $itemData) {
                $noAset = trim($itemData['no_aset'] ?? '');
                $snLama = trim($itemData['sn_lama'] ?? '');
                if (! empty($noAset) || ! empty($snLama)) {
                    $hasItem = true;
                    break;
                }
            }
        }

        if (! $hasItem) {
            return back()->withErrors(['Harap pilih atau isi minimal satu item aset.'])->withInput();
        }

        $extraData = null;
        if ($ticket->type === 'serah_terima') {
            $extraData = [
                'nama_tl' => $request->input('team_leader'),
                'no_ktp' => $request->input('nik_ktp'),
                'alamat_ktp' => $request->input('alamat_ktp'),
                'domisili' => $request->input('domisili'),
                'ruangan' => $request->input('ruangan'),
            ];
        } elseif ($ticket->type === 'peminjaman' && $ticket->borrow_type === 'luar') {
            $extraData = [
                'nama_tl' => $request->input('team_leader'),
                'no_ktp' => $request->input('nik_ktp'),
                'alamat_ktp' => $request->input('alamat_ktp'),
                'domisili' => $request->input('domisili'),
                'ruangan' => $request->input('ruangan'),
            ];
        }

        // Buat transaksi
        $transaction = Transaction::create([
            'ticket_id' => $ticket->id,
            'doc_number' => null, // Di-generate saat Admin validasi
            'type' => $ticket->type,
            'borrow_type' => $ticket->borrow_type,
            'nama_pengaju' => $request->nama,
            'department' => $request->department,
            'no_wa' => $request->no_wa,
            'status' => 'menunggu_validasi',
            'catatan_admin' => $extraData ? json_encode($extraData) : null,
            'spv_name' => ($ticket->type === 'peminjaman' && $ticket->borrow_type === 'luar') ? $request->spv_name : null,
            'hrd_name' => ($ticket->type === 'peminjaman' && $ticket->borrow_type === 'luar') ? $request->hrd_name : null,
        ]);

        if ($ticket->type === 'peminjaman' && $ticket->borrow_type === 'luar') {
            foreach ($request->luar_items as $sn) {
                $inv = Inventory::where('sn', $sn)->first();
                if ($inv) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'kategori' => $inv->jenis,
                        'no_aset' => $sn,
                        'keterangan' => $inv->keterangan,
                        'foto_path' => '',
                    ]);
                }
            }
        } else {
            // Simpan item (bisa kategori statis atau dinamis berdasarkan SN)
            $files = $request->file('items', []);

            foreach ($items as $key => $itemData) {
                $noAset = trim($itemData['no_aset'] ?? '');
                $snLama = trim($itemData['sn_lama'] ?? '');

                // Lewati jika kosong
                if (empty($noAset) && empty($snLama)) {
                    continue;
                }

                $kategori = $itemData['kategori'] ?? (in_array($key, self::KATEGORI_LIST) ? $key : 'Unknown');

                $fotoPath = null;
                if (isset($files[$key]['foto'])) {
                    $fotoPath = $files[$key]['foto']->store('bukti', 'public');
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'kategori' => $kategori,
                    'no_aset' => strtoupper($noAset),
                    'sn_lama' => strtoupper($snLama),
                    'alasan_penukaran' => $itemData['alasan_penukaran'] ?? null,
                    'penjelasan_kerusakan' => $itemData['penjelasan_kerusakan'] ?? null,
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'foto_path' => $fotoPath ?? '',
                ]);
            }
        }

        // Update status tiket
        $ticket->update([
            'status' => 'menunggu_validasi',
            'filled_at' => now(),
        ]);

        return view('user.tiket', [
            'type' => $type,
            'id' => $ticketCode,
            'ticket' => $ticket,
            'submitted' => true,
        ]);
    }
}
