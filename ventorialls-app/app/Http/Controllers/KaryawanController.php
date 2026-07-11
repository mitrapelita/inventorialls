<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['nullable', 'email', 'unique:users,email'],
            'id_karyawan'  => ['nullable', 'string', 'max:50', 'unique:users,id_karyawan'],
            'department'   => ['required', 'string', 'max:100'],
            'posisi'       => ['nullable', 'string', 'max:100'],
            'kontak'       => ['nullable', 'string', 'max:20'],
            'alamat'       => ['nullable', 'string'],
            'nama_tl'      => ['nullable', 'string', 'max:255'],
            'no_ktp'       => ['nullable', 'string', 'max:20'],
            'alamat_ktp'   => ['nullable', 'string'],
            'domisili'     => ['nullable', 'string', 'max:255'],
            'ruangan'      => ['nullable', 'string', 'max:100'],
            'status_kerja' => ['required', 'in:Aktif,Resign'],
        ]);

        $data['role']     = 'karyawan';
        $data['password'] = Hash::make('karyawan123');

        $user = User::create($data);

        ActivityLog::record(
            'created', 'User', $user->id,
            "Admin menambahkan data karyawan baru: {$user->name} ({$user->department}).",
            ['karyawan' => $user->name, 'department' => $user->department]
        );

        return back()->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function print(User $user)
    {
        $items = Inventory::where('pengguna', $user->name)->get();
        return view('admin.print.karyawan', compact('user', 'items'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['nullable', 'email', "unique:users,email,{$user->id}"],
            'id_karyawan'  => ['nullable', 'string', 'max:50', "unique:users,id_karyawan,{$user->id}"],
            'department'   => ['required', 'string', 'max:100'],
            'posisi'       => ['nullable', 'string', 'max:100'],
            'kontak'       => ['nullable', 'string', 'max:20'],
            'alamat'       => ['nullable', 'string'],
            'nama_tl'      => ['nullable', 'string', 'max:255'],
            'no_ktp'       => ['nullable', 'string', 'max:20'],
            'alamat_ktp'   => ['nullable', 'string'],
            'domisili'     => ['nullable', 'string', 'max:255'],
            'ruangan'      => ['nullable', 'string', 'max:100'],
            'status_kerja' => ['required', 'in:Aktif,Resign'],
        ]);

        $user->update($data);

        ActivityLog::record(
            'updated', 'User', $user->id,
            "Admin memperbarui data karyawan: {$user->name}.",
            ['karyawan' => $user->name]
        );

        return back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $nama = $user->name;

        // Simpan snapshot aset yang sedang dipakai (sebelum dikosongkan)
        $asetAktif = Inventory::where('pengguna', $nama)->where('status', 'Aktif')->get();
        $snapshot = $asetAktif->map(fn($a) => [
            'id'          => $a->id,
            'pengguna'    => $nama,
            'kontak'      => $a->kontak,
            'department'  => $a->department,
            'team_leader' => $a->team_leader,
            'lokasi'      => $a->lokasi,
        ])->values()->toArray();

        // Simpan snapshot ke data karyawan agar bisa di-restore
        $user->aset_tersimpan = $snapshot;
        $user->save();

        // Kembalikan barang yang sedang dipakai ke status Disimpan
        Inventory::where('pengguna', $nama)->update([
            'status'     => 'Disimpan',
            'lokasi'     => 'Ruangan IT',
            'pengguna'   => null,
            'kontak'     => null,
            'department' => null,
            'team_leader'=> null,
        ]);

        $user->delete();

        ActivityLog::record(
            'deleted', 'User', null,
            "Admin menghapus data karyawan: {$nama}.",
            ['karyawan' => $nama]
        );

        return back()->with('success', 'Data karyawan berhasil dihapus (dapat dipulihkan dalam 30 hari).');
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->pin !== '447747') {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }

        $ids = $request->ids ?? [];
        if (count($ids) > 0) {
            $users = User::whereIn('id', $ids)->get();
            foreach ($users as $user) {
                // Simpan snapshot aset sebelum dikosongkan
                $asetAktif = \App\Models\Inventory::where('pengguna', $user->name)->where('status', 'Aktif')->get();
                $snapshot = $asetAktif->map(fn($a) => [
                    'id'          => $a->id,
                    'pengguna'    => $user->name,
                    'kontak'      => $a->kontak,
                    'department'  => $a->department,
                    'team_leader' => $a->team_leader,
                    'lokasi'      => $a->lokasi,
                ])->values()->toArray();

                $user->aset_tersimpan = $snapshot;
                $user->save();

                // Lepas aset
                \App\Models\Inventory::where('pengguna', $user->name)->update([
                    'status'     => 'Disimpan',
                    'lokasi'     => 'Ruangan IT',
                    'pengguna'   => null,
                    'kontak'     => null,
                    'department' => null,
                    'team_leader'=> null,
                ]);

                ActivityLog::record(
                    'deleted', 'User', null,
                    "Admin menghapus massal data karyawan: {$user->name}.",
                    ['karyawan' => $user->name]
                );

                $user->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        // Re-link semua aset yang tersimpan kembali ke karyawan ini
        $snapshot = $user->aset_tersimpan ?? [];
        foreach ($snapshot as $asetData) {
            Inventory::where('id', $asetData['id'])->update([
                'status'     => 'Aktif',
                'pengguna'   => $asetData['pengguna'],
                'kontak'     => $asetData['kontak'],
                'department' => $asetData['department'],
                'team_leader'=> $asetData['team_leader'],
                'lokasi'     => $asetData['lokasi'],
            ]);
        }

        // Hapus snapshot setelah berhasil di-restore
        $user->aset_tersimpan = null;
        $user->saveQuietly();

        ActivityLog::record(
            'restored', 'User', $user->id,
            "Admin memulihkan data karyawan: {$user->name}. " . count($snapshot) . " aset dikembalikan.",
            ['karyawan' => $user->name]
        );

        return back()->with('success', "Karyawan {$user->name} berhasil dipulihkan, " . count($snapshot) . " aset aktif kembali!");
    }
}
