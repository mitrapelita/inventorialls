<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Buat tiket baru (disimpan ke DB)
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'        => ['required', 'in:serah_terima,peminjaman,penukaran'],
            'borrow_type' => ['nullable', 'in:dalam,luar'],
        ]);

        $type = $request->type;

        $ticket = Ticket::create([
            'ticket_code' => Ticket::generateCode($type),
            'type'        => $type,
            'borrow_type' => $request->borrow_type,
            'status'      => 'menunggu_diisi',
            'created_by'  => Auth::id(),
        ]);

        $redirectType = str_replace('_', '-', $type);
        return redirect()->route('admin-tiket', ['type' => $redirectType])
            ->with('success', "Tiket {$ticket->ticket_code} berhasil dibuat!");
    }

    /**
     * Batalkan tiket
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->update(['status' => 'dibatalkan']);
        return redirect()->route('admin-tiket')
            ->with('success', "Tiket {$ticket->ticket_code} telah dibatalkan.");
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->pin !== '447747') {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }

        $ids = $request->ids ?? [];
        if (count($ids) > 0) {
            Ticket::whereIn('id', $ids)->update(['status' => 'dibatalkan']);
            // also log
            foreach($ids as $id) {
                \App\Models\ActivityLog::record('deleted', 'Ticket', $id, "Admin membatalkan massal tiket ID: {$id}");
            }
        }

        return response()->json(['success' => true]);
    }
}
