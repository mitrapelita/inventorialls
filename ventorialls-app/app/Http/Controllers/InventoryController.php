<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis'          => ['required', 'string', 'max:100'],
            'merk'           => ['required', 'string', 'max:255'],
            'sn'             => ['required', 'string', 'max:100', 'unique:inventories,sn'],
            'tanggal_masuk'  => ['nullable', 'date'],
            'kepemilikan'    => ['required', 'in:PTMPTB,Vendor'],
            'pengguna'       => ['nullable', 'string', 'max:255'],
            'kontak'         => ['nullable', 'string', 'max:20'],
            'department'     => ['nullable', 'string', 'max:100'],
            'team_leader'    => ['nullable', 'string', 'max:255'],
            'tanggal_signin' => ['nullable', 'date'],
            'lokasi'         => ['nullable', 'in:Di MPTB,Ruangan IT'],
            'hak_bawa_pulang'=> ['nullable', 'boolean'],
            'kondisi'        => ['required', 'in:Baik,Rusak'],
            'status'         => ['required', 'in:Aktif,Disimpan,Return Vendor'],
            'keterangan'     => ['nullable', 'string'],
        ], [
            'sn.unique' => 'Nomor Aset (SN) ini sudah terdaftar di Master Data. Silakan gunakan Nomor Aset yang lain.',
        ]);

        $inventory = Inventory::create($data);

        ActivityLog::record(
            'created', 'Inventory', $inventory->id,
            "Admin menambahkan aset baru: {$inventory->jenis} — {$inventory->merk} (SN: {$inventory->sn}).",
            ['sn' => $inventory->sn, 'jenis' => $inventory->jenis, 'merk' => $inventory->merk]
        );

        return back()->with('success', 'Aset berhasil ditambahkan!');
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'jenis'          => ['required', 'string', 'max:100'],
            'merk'           => ['required', 'string', 'max:255'],
            'sn'             => ['required', 'string', 'max:100', "unique:inventories,sn,{$inventory->id}"],
            'tanggal_masuk'  => ['nullable', 'date'],
            'kepemilikan'    => ['required', 'in:PTMPTB,Vendor'],
            'pengguna'       => ['nullable', 'string', 'max:255'],
            'kontak'         => ['nullable', 'string', 'max:20'],
            'department'     => ['nullable', 'string', 'max:100'],
            'team_leader'    => ['nullable', 'string', 'max:255'],
            'tanggal_signin' => ['nullable', 'date'],
            'lokasi'         => ['nullable', 'in:Di MPTB,Ruangan IT'],
            'hak_bawa_pulang'=> ['nullable', 'boolean'],
            'kondisi'        => ['required', 'in:Baik,Rusak'],
            'status'         => ['required', 'in:Aktif,Disimpan,Return Vendor'],
            'keterangan'     => ['nullable', 'string'],
        ], [
            'sn.unique' => 'Nomor Aset (SN) ini sudah terdaftar di Master Data. Silakan gunakan Nomor Aset yang lain.',
        ]);

        $inventory->update($data);

        ActivityLog::record(
            'updated', 'Inventory', $inventory->id,
            "Admin memperbarui data aset: {$inventory->jenis} — {$inventory->merk} (SN: {$inventory->sn}).",
            ['sn' => $inventory->sn]
        );

        return back()->with('success', 'Data aset berhasil diperbarui!');
    }

    public function destroy(Inventory $inventory)
    {
        $desc = "{$inventory->jenis} — {$inventory->merk} (SN: {$inventory->sn})";
        $inventory->delete();

        ActivityLog::record(
            'deleted', 'Inventory', null,
            "Admin menghapus aset: {$desc}.",
            ['sn' => $inventory->sn, 'desc' => $desc]
        );

        return back()->with('success', 'Aset berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->pin !== \App\Models\Setting::get('validation_pin', env('VALIDATION_PIN', '447747'))) {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }

        $ids = $request->ids ?? [];
        if (count($ids) > 0) {
            $items = Inventory::whereIn('id', $ids)->get();
            foreach ($items as $item) {
                ActivityLog::create([
                    'admin_id' => auth()->id(),
                    'action' => 'deleted',
                    'model_type' => 'Inventory',
                    'model_id' => $item->id,
                    'description' => "Menghapus massal data inventaris: {$item->name} ({$item->sn})"
                ]);
                $item->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    public function import(Request $request)
    {
        $items = $request->json()->all();
        if (!is_array($items)) {
            return response()->json(['success' => false, 'message' => 'Format data tidak valid'], 400);
        }

        $successCount = 0;
        $skippedItems = [];
        $processedSns = [];

        foreach ($items as $item) {
            if (empty($item['jenis']) || empty($item['merk']) || empty($item['sn'])) {
                continue;
            }

            $sn = $item['sn'];

            // Cek duplikat dalam file Excel itu sendiri
            if (in_array($sn, $processedSns)) {
                $skippedItems[] = [
                    'sn' => $sn,
                    'merk' => $item['merk'],
                    'reason' => 'Duplikat dalam file Excel'
                ];
                continue;
            }

            // Cek duplikat di database
            $exists = Inventory::where('sn', $sn)->exists();
            if ($exists) {
                $skippedItems[] = [
                    'sn' => $sn,
                    'merk' => $item['merk'],
                    'reason' => 'Sudah ada di database'
                ];
                $processedSns[] = $sn;
                continue;
            }

            // Simpan data
            Inventory::create([
                'jenis'          => $item['jenis'],
                'merk'           => $item['merk'],
                'sn'             => $sn,
                'tanggal_masuk'  => now()->format('Y-m-d'),
                'kepemilikan'    => 'PTMPTB',
                'lokasi'         => 'Ruangan IT',
                'kondisi'        => 'Baik',
                'status'         => 'Disimpan',
                'hak_bawa_pulang'=> false,
            ]);
            
            $processedSns[] = $sn;
            $successCount++;
        }

        if ($successCount > 0) {
            ActivityLog::record(
                'import', 'Inventory', null,
                "Admin mengimpor {$successCount} data inventaris baru dari file Excel."
            );
        }

        return response()->json([
            'success' => true, 
            'success_count' => $successCount,
            'skipped_count' => count($skippedItems),
            'skipped_items' => $skippedItems,
            'message' => "Berhasil mengimpor {$successCount} data inventaris."
        ]);
    }
}
