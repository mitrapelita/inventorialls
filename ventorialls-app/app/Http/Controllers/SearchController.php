<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Inventory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Cari Karyawan berdasarkan Nama, Kontak, atau ID Karyawan.
     */
    public function searchKaryawan(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json(['found' => false]);
        }

        $karyawan = User::where('role', 'karyawan')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('kontak', 'LIKE', "%{$query}%")
                  ->orWhere('id_karyawan', 'LIKE', "%{$query}%");
            })
            ->first();

        if ($karyawan) {
            // Dapatkan aset aktif milik karyawan ini
            $asetAktif = Inventory::where('pengguna', $karyawan->name)
                ->where('status', 'Aktif')
                ->get(['id', 'jenis', 'merk', 'sn', 'kondisi', 'status', 'hak_bawa_pulang'])
                ->toArray();

            return response()->json([
                'found' => true,
                'data' => [
                    'id'           => $karyawan->id,
                    'nama'         => $karyawan->name,
                    'kontak'       => $karyawan->kontak,
                    'id_karyawan'  => $karyawan->id_karyawan,
                    'department'   => $karyawan->department,
                    'posisi'       => $karyawan->posisi,
                    'nama_tl'      => $karyawan->nama_tl,
                    'no_ktp'       => $karyawan->no_ktp,
                    'alamat_ktp'   => $karyawan->alamat_ktp,
                    'domisili'     => $karyawan->domisili,
                    'ruangan'      => $karyawan->ruangan,
                    'items'        => $asetAktif,
                ]
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * Cari Inventory (Aset) berdasarkan Nomor Aset (sn).
     */
    public function searchInventory(Request $request)
    {
        $rawQuery = $request->get('q', $request->get('sn', ''));
        $sn = strtoupper(trim($rawQuery));

        if (empty($sn)) {
            return response()->json(['found' => false]);
        }

        $inventory = Inventory::where('sn', $sn)->first();

        if ($inventory) {
            // Tentukan status pengguna saat ini dari data real
            $penggunaLabel = $inventory->pengguna
                ? "{$inventory->pengguna} ({$inventory->department})"
                : 'Gudang (Tersedia)';

            return response()->json([
                'found' => true,
                'data' => [
                    'id'          => $inventory->id,
                    'sn'          => $inventory->sn,
                    'merk'        => $inventory->merk,
                    'jenis'       => $inventory->jenis,
                    'nama_barang' => $inventory->jenis . ' ' . $inventory->merk,
                    'pengguna'    => $penggunaLabel,
                    'department'  => $inventory->department ?? '-',
                    'kondisi'     => $inventory->kondisi,
                    'status'      => $inventory->status,
                ]
            ]);
        }

        return response()->json(['found' => false]);
    }
}
