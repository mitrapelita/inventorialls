<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\StockMutation;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_aset'     => Inventory::count(),
            'aset_aktif'     => Inventory::where('status', 'Aktif')->count(),
            'aset_disimpan'  => Inventory::where('status', 'Disimpan')->where('kondisi', 'Baik')->count(),
            'aset_rusak'     => Inventory::where('status', 'Disimpan')->where('kondisi', 'Rusak')->count(),
            'return_vendor'  => Inventory::where('status', 'Return Vendor')->count(),
            'total_karyawan' => User::where('role', 'karyawan')->where('status_kerja', 'Aktif')->count(),
            'tiket_pending'  => Transaction::where('status', 'menunggu_validasi')->count(),
        ];

        $breakdown_aktif = Inventory::where('status', 'Aktif')
            ->selectRaw('jenis as kategori, count(*) as count')
            ->groupBy('jenis')->pluck('count', 'kategori');
            
        $breakdown_tersedia = Inventory::where('status', 'Disimpan')->where('kondisi', 'Baik')
            ->selectRaw('jenis as kategori, count(*) as count')
            ->groupBy('jenis')->pluck('count', 'kategori');
            
        $breakdown_return = Inventory::where('status', 'Return Vendor')
            ->selectRaw('jenis as kategori, count(*) as count')
            ->groupBy('jenis')->pluck('count', 'kategori');
            
        $breakdown_rusak = Inventory::where('status', 'Disimpan')->where('kondisi', 'Rusak')
            ->selectRaw('jenis as kategori, count(*) as count')
            ->groupBy('jenis')->pluck('count', 'kategori');

        // Data for Recent Activity Log
        $recent_activities = \App\Models\ActivityLog::with('admin')->latest()->take(8)->get();

        // Data for Chart (Activity count per day for the last 7 days)
        $chart_data = \App\Models\ActivityLog::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('count', 'date');

        // Fill missing days with 0 for chart
        $chart_labels = [];
        $chart_values = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $chart_labels[] = now()->subDays($i)->format('d M');
            $chart_values[] = $chart_data->has($dateStr) ? $chart_data[$dateStr] : 0;
        }

        return view('admin.dashboard', compact('stats', 'breakdown_aktif', 'breakdown_tersedia', 'breakdown_return', 'breakdown_rusak', 'recent_activities', 'chart_labels', 'chart_values'));

    }

    public function master()
    {
        $inventories = Inventory::latest()->get();
        return view('admin.master', compact('inventories'));
    }

    public function karyawan()
    {
        $karyawans = User::where('role', 'karyawan')->latest()->get()->map(function($user) {
            $asets = $user->asetAktif();
            $user->total_aset = $asets->count();
            $user->daftar_aset = $asets;
            return $user;
        });

        $trashedKaryawans = User::onlyTrashed()->where('role', 'karyawan')->latest()->get();

        return view('admin.karyawan', compact('karyawans', 'trashedKaryawans'));
    }

    public function validasi()
    {
        $transactions = Transaction::with(['ticket', 'items'])
            ->where('status', 'menunggu_validasi')
            ->latest()
            ->get();
        return view('admin.validasi', compact('transactions'));
    }

    public function transaksi()
    {
        $serahTerima = Transaction::with('items')
            ->where('type', 'serah_terima')
            ->latest()->get();
        $activeBorrowers = \App\Models\Inventory::where('status', 'Aktif')
            ->whereNotNull('pengguna')
            ->get()
            ->groupBy('pengguna')
            ->map(function ($items, $pengguna) {
                $user = \App\Models\User::where('name', $pengguna)->first();
                $history = \App\Models\Transaction::where('nama_pengaju', $pengguna)
                            ->with('items')
                            ->orderBy('created_at', 'desc')
                            ->get();
                return [
                    'id' => $user ? $user->id : uniqid(),
                    'peminjam' => $pengguna,
                    'dept' => $user ? $user->department : '-',
                    'id_karyawan' => $user ? $user->id_karyawan : '-',
                    'posisi' => $user ? $user->posisi : '-',
                    'kontak' => $user ? $user->kontak : '-',
                    'no_ktp' => $user ? $user->no_ktp : '-',
                    'alamat_ktp' => $user ? $user->alamat_ktp : '-',
                    'domisili' => $user ? $user->domisili : '-',
                    'barang' => $items->count() . ' Barang',
                    'pinjam_dalam' => $items->where('hak_bawa_pulang', false)->count(),
                    'pinjam_luar'  => $items->where('hak_bawa_pulang', true)->count(),
                    'items_data' => $items->map(fn($i) => [
                        'kategori'        => $i->jenis,
                        'no_aset'         => $i->sn,
                        'keterangan'      => $i->keterangan,
                        'lokasi'          => $i->lokasi,
                        'hak_bawa_pulang' => (bool) $i->hak_bawa_pulang,
                    ])->values(),
                    'history' => $history->map(fn($t) => [
                        'doc' => $t->doc_number,
                        'type' => $t->type,
                        'tanggal' => $t->created_at->format('d M Y'),
                        'tanggal_raw' => $t->created_at->format('Y-m-d'),
                        'status' => $t->status,
                    ])->values()
                ];
            })->values();

        $penukaran = Transaction::with('items')
            ->where('type', 'penukaran')
            ->latest()->get();

        // Daftar karyawan untuk dropdown di form peminjaman & penukaran
        $karyawans = User::where('role', 'karyawan')->where('status_kerja', 'Aktif')->orderBy('name')->get();

        // Helper to map items for frontend display
        $mapItems = fn($i) => [
            'kategori'        => $i->kategori,
            'no_aset'         => $i->no_aset,
            'keterangan'      => $i->keterangan,
            'sn_lama'         => $i->sn_lama,
            'hak_bawa_pulang' => false, // default for transaction items (not applicable)
        ];

        // Fetch history peminjaman/pengembalian
        $historyPeminjaman = Transaction::with('items')
            ->whereIn('type', ['peminjaman', 'pengembalian'])
            ->latest()->get();

        return view('admin.transaksi', compact('serahTerima', 'activeBorrowers', 'penukaran', 'historyPeminjaman', 'karyawans', 'mapItems'));
    }

    public function mutasi()
    {
        $masuk  = StockMutation::where('type', 'masuk')->with('creator')->latest()->get();
        
        $keluarAll = \App\Models\Transaction::with('items.inventory')->where('type', 'barang_keluar')->latest()->get();
        $keluarActive = collect();
        $keluarHistory = collect();

        foreach ($keluarAll as $t) {
            $allReturned = $t->items->count() > 0 && $t->items->every(fn($item) => $item->is_returned);
            if ($allReturned) {
                $keluarHistory->push($t);
            } else {
                $keluarActive->push($t);
            }
        }

        return view('admin.mutasi', compact('masuk', 'keluarActive', 'keluarHistory'));
    }

    public function laporan(Request $request)
    {
        $q = $request->get('q');
        $inventory = null;
        $histori = collect();

        if ($q) {
            $inventory = Inventory::where('sn', $q)->first();
            if ($inventory) {
                // Ambil semua transaksi yang menyebut no_aset ini (di TransactionItem, kolomnya no_aset atau sn_lama)
                $histori = \App\Models\TransactionItem::with(['transaction'])
                    ->where('no_aset', $inventory->sn)
                    ->orWhere('sn_lama', $q)
                    ->latest()
                    ->get();
            }
        }

        return view('admin.laporan', compact('inventory', 'histori', 'q'));
    }

    public function log()
    {
        $logs = ActivityLog::with('admin')->latest()->paginate(50);
        return view('admin.log', compact('logs'));
    }

    public function tiket($type)
    {
        $id = request('id');
        $ticket = null;
        if ($id) {
            $ticket = \App\Models\Ticket::where('ticket_code', $id)
                ->where('type', str_replace('-', '_', $type))
                ->where('status', 'menunggu_diisi')
                ->first();
        }
        return view('user.tiket', compact('type', 'id', 'ticket'));
    }

    // ─── Halaman Histori Barang ────────────────────────────────────────────────
    public function historiBarang(Request $request)
    {
        $sn = $request->get('sn');
        $inventory = null;
        $histori = collect();

        if ($sn) {
            $inventory = Inventory::where('sn', $sn)->first();
            if ($inventory) {
                // Ambil semua transaksi yang menyebut no_aset ini
                $histori = \App\Models\TransactionItem::with(['transaction'])
                    ->where('no_aset', $sn)
                    ->orWhere('sn_lama', $sn)
                    ->latest()
                    ->get();
            }
        }

        return view('admin.histori', compact('inventory', 'histori', 'sn'));
    }

    // --- User Portal ---
    public function userDashboard()
    {
        return view('user.dashboard');
    }

    public function userSerahTerima()
    {
        $tickets = Ticket::where('type', 'serah_terima')
            ->where(function ($query) {
                $query->whereIn('status', ['menunggu_diisi', 'menunggu_validasi'])
                      ->orWhere(function ($q) {
                          $q->where('status', 'selesai')
                            ->whereDate('updated_at', today());
                      });
            })
            ->orderByRaw("CASE WHEN status IN ('menunggu_diisi', 'menunggu_validasi') THEN 1 ELSE 2 END")
            ->latest()->get();
        return view('user.serah-terima', compact('tickets'));
    }

    public function userPeminjaman()
    {
        $tickets = Ticket::where('type', 'peminjaman')
            ->where(function ($query) {
                $query->whereIn('status', ['menunggu_diisi', 'menunggu_validasi'])
                      ->orWhere(function ($q) {
                          $q->where('status', 'selesai')
                            ->whereDate('updated_at', today());
                      });
            })
            ->orderByRaw("CASE WHEN status IN ('menunggu_diisi', 'menunggu_validasi') THEN 1 ELSE 2 END")
            ->latest()->get();
        return view('user.peminjaman', compact('tickets'));
    }

    public function userPenukaran()
    {
        $tickets = Ticket::where('type', 'penukaran')
            ->where(function ($query) {
                $query->whereIn('status', ['menunggu_diisi', 'menunggu_validasi'])
                      ->orWhere(function ($q) {
                          $q->where('status', 'selesai')
                            ->whereDate('updated_at', today());
                      });
            })
            ->orderByRaw("CASE WHEN status IN ('menunggu_diisi', 'menunggu_validasi') THEN 1 ELSE 2 END")
            ->latest()->get();
        return view('user.penukaran', compact('tickets'));
    }
}
