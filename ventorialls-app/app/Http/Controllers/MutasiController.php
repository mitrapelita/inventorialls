<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    /**
     * API untuk mengambil daftar barang (Inventory) yang kondisinya Rusak dan status Disimpan
     */
    public function apiInventoryRusak()
    {
        $items = Inventory::where('status', 'Disimpan')
            ->where('kondisi', 'Rusak')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Store Barang Masuk (Pencatatan manual/statis ke tabel stock_mutations)
     */
    public function storeMasuk(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'satuan' => 'nullable|string|max:50',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        StockMutation::create([
            'type' => 'masuk',
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'keterangan' => $request->keterangan,
            'created_by' => Auth::id(),
            'created_at' => $request->tanggal.' '.now()->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pencatatan Barang Masuk berhasil disimpan!',
        ]);
    }

    /**
     * Hapus Satu Data Barang Masuk
     */
    public function destroyMasuk($id)
    {
        try {
            $mutation = StockMutation::findOrFail($id);
            $mutation->delete();

            return response()->json(['success' => true, 'message' => 'Data Barang Masuk berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Beberapa Data Barang Masuk (Bulk Delete)
     */
    public function bulkDestroyMasuk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:stock_mutations,id',
        ]);

        try {
            StockMutation::whereIn('id', $request->ids)->delete();

            return response()->json(['success' => true, 'message' => count($request->ids).' data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store Barang Keluar (Multi-select inventory, catat ke transactions & update status barang)
     */
    public function storeKeluar(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'alamat_tujuan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1', // array of inventory_id
            'items.*' => 'exists:inventories,id',
        ]);

        DB::beginTransaction();
        try {
            // Generate Document Number untuk BAST/BK
            $docNumber = Transaction::generateDocNumber('barang_keluar');

            // Buat record Transaction
            $transaction = Transaction::create([
                'doc_number' => $docNumber,
                'type' => 'barang_keluar',
                'status' => 'selesai',
                'nama_pengaju' => Auth::user()->name,
                'department' => Auth::user()->department ?? 'IT',
                'alamat_tujuan' => $request->alamat_tujuan,
                'keterangan' => $request->keterangan,
                'created_at' => $request->tanggal.' '.now()->format('H:i:s'),
                'validated_by' => Auth::id(),
                'validated_at' => now(),
            ]);

            // Ambil semua data inventory yang dipilih (jika ada)
            if ($request->has('items') && is_array($request->items)) {
                $inventories = Inventory::whereIn('id', $request->items)->get();

                foreach ($inventories as $inv) {
                    // Catat ke TransactionItem
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'inventory_id' => $inv->id,
                        'kategori' => $inv->jenis,
                        'no_aset' => $inv->sn,
                        'keterangan' => $inv->keterangan,
                        'jumlah' => 1,
                    ]);

                    // Update status barang menjadi Return Vendor
                    $inv->update([
                        'status' => 'Return Vendor',
                    ]);
                }
            }

            // Simpan manual items (jika ada)
            if ($request->has('manual_items') && is_array($request->manual_items)) {
                foreach ($request->manual_items as $mItem) {
                    if (!empty($mItem['nama']) && !empty($mItem['jumlah'])) {
                        TransactionItem::create([
                            'transaction_id' => $transaction->id,
                            'inventory_id' => null,
                            'kategori' => 'Manual / Aksesoris',
                            'no_aset' => '-',
                            'keterangan' => $mItem['nama'],
                            'jumlah' => (int) $mItem['jumlah'],
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proses Barang Keluar berhasil disimpan!',
                'transaction_id' => $transaction->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Terima Kembali Barang Keluar
     */
    public function terimaKembali(Request $request, Transaction $transaction)
    {
        $request->validate([
            'returned_items' => 'required|array|min:1',
            'returned_items.*' => 'exists:transaction_items,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->returned_items as $itemId) {
                $item = TransactionItem::find($itemId);
                if ($item && $item->transaction_id == $transaction->id && ! $item->is_returned) {
                    $item->update(['is_returned' => true]);

                    $kondisi = $request->input("returned_conditions.{$itemId}", 'Baik');

                    // Update status master inventory menjadi Disimpan dan set kondisi
                    if ($item->inventory_id) {
                        $inv = Inventory::find($item->inventory_id);
                        if ($inv) {
                            $inv->update(['status' => 'Disimpan', 'kondisi' => $kondisi]);
                        }
                    } else {
                        // Fallback untuk data lama
                        $inv = Inventory::where('sn', $item->no_aset)->first();
                        if ($inv) {
                            $inv->update(['status' => 'Disimpan', 'kondisi' => $kondisi]);
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status barang berhasil diupdate menjadi Disimpan!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: Kembalikan status inventory menjadi Disimpan sebelum Transaction dihapus
     */
    private function revertInventoryStatus($transaction)
    {
        foreach ($transaction->items as $item) {
            if ($item->inventory_id) {
                Inventory::where('id', $item->inventory_id)->update(['status' => 'Disimpan']);
            } else {
                Inventory::where('sn', $item->no_aset)->update(['status' => 'Disimpan']);
            }
        }
    }

    /**
     * Hapus Satu Data Barang Keluar
     */
    public function destroyKeluar(Transaction $transaction)
    {
        try {
            $this->revertInventoryStatus($transaction);
            $transaction->delete(); // Otomatis hapus item karena cascade

            return response()->json(['success' => true, 'message' => 'Data Barang Keluar berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Beberapa Data Barang Keluar (Bulk Delete)
     */
    public function bulkDestroyKeluar(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:transactions,id',
        ]);

        try {
            $transactions = Transaction::with('items')->whereIn('id', $request->ids)->get();
            foreach ($transactions as $t) {
                $this->revertInventoryStatus($t);
                $t->delete();
            }

            return response()->json(['success' => true, 'message' => count($request->ids).' data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Print Berita Acara Barang Keluar
     */
    public function printKeluar(Transaction $transaction)
    {
        if ($transaction->type !== 'barang_keluar') {
            abort(404, 'Dokumen tidak ditemukan atau bukan tipe Barang Keluar.');
        }

        $transaction->load('items');
        App::setLocale('id'); // Bahasa Indonesia

        return view('admin.print.barang-keluar', compact('transaction'));
    }
}
