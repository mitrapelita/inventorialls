<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\ActivityLog;

class PublicValidationController extends Controller
{
    const KATEGORI_LIST = [
        'laptop', 'monitor', 'keyboard', 'mouse', 'charger', 'headset', 'converter_hub', 'drawing_tablet'
    ];

    public function index()
    {
        return view('public.validasi-fisik');
    }

    public function indexUser()
    {
        return view('user.cek-aset');
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan.nama'       => ['required', 'string', 'max:255'],
            'karyawan.no_wa'      => ['required', 'string', 'max:20'],
            'karyawan.department' => ['required', 'string', 'max:100'],
        ]);

        $kData = $request->karyawan;

        // Data ekstra karyawan disimpan sementara di catatan_admin sebagai JSON
        $extraData = [
            'nama_tl'      => $kData['team_leader'] ?? ($kData['nama_tl'] ?? null),
            'no_ktp'       => $kData['nik_ktp'] ?? ($kData['no_ktp'] ?? null),
            'alamat_ktp'   => $kData['alamat_ktp'] ?? null,
            'domisili'     => $kData['domisili'] ?? null,
            'ruangan'      => $kData['ruangan'] ?? null,
        ];

        $ticket = Ticket::create([
            'ticket_code' => Ticket::generateCode('serah_terima'),
            'type'        => 'serah_terima',
            'status'      => 'menunggu_validasi',
            'created_by'  => auth()->id() ?? \App\Models\User::first()->id, // Fallback to first user (Admin) if public
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

        ActivityLog::record(
            'created', 'Transaction', $transaction->id,
            "Publik/Karyawan membuat draft Serah Terima via Tools Validasi (Tiket: {$ticket->ticket_code}) untuk {$kData['nama']}.",
            ['ticket_code' => $ticket->ticket_code, 'karyawan' => $kData['nama']]
        );

        return redirect()->back()->with('success', 'Data berhasil disimpan dan menunggu validasi admin!');
    }

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
}
