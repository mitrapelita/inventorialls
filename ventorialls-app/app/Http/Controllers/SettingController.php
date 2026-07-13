<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan (daftar admin)
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        $approvers = \App\Models\Approver::all();
        return view('admin.pengaturan', compact('admins', 'approvers'));
    }

    /**
     * Tambah admin baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id_karyawan' => 'required|string|max:255|unique:users,id_karyawan',
            'password' => 'required|string|min:6',
        ], [
            'id_karyawan.unique' => 'Username/ID Karyawan sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        User::create([
            'name' => $request->name,
            'id_karyawan' => $request->id_karyawan,
            'email' => $request->id_karyawan . '@ventorialls.co',
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->back()->with('success', 'Admin berhasil ditambahkan');
    }

    /**
     * Update data admin
     */
    public function update(Request $request, User $user)
    {
        // Pastikan yang diupdate adalah admin
        if ($user->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'id_karyawan' => ['required', 'string', 'max:255', Rule::unique('users', 'id_karyawan')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
        ], [
            'id_karyawan.unique' => 'Username/ID Karyawan sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        $data = [
            'name' => $request->name,
            'id_karyawan' => $request->id_karyawan,
            'email' => $request->id_karyawan . '@ventorialls.co',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data admin berhasil diperbarui');
    }

    /**
     * Hapus admin
     */
    public function destroy(User $user)
    {
        // Pastikan yang dihapus adalah admin
        if ($user->role !== 'admin') {
            abort(403);
        }

        // Cek apakah ini admin terakhir
        if (User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus satu-satunya admin di sistem.');
        }

        // Cegah hapus diri sendiri (bisa juga diizinkan jika diinginkan, tapi amannya dicegah)
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Admin berhasil dihapus');
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->pin !== '447747') {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }

        $ids = $request->ids ?? [];
        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $user = User::find($id);
                if ($user && $user->role === 'admin') {
                    if (User::where('role', 'admin')->count() > 1 && $user->id !== auth()->id()) {
                        $user->delete();
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function storeApprover(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:SPV,HRD',
        ]);

        \App\Models\Approver::create([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Data SPV/HRD berhasil ditambahkan');
    }

    public function updateApprover(Request $request, \App\Models\Approver $approver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:SPV,HRD',
        ]);

        $approver->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Data SPV/HRD berhasil diperbarui');
    }

    public function destroyApprover(\App\Models\Approver $approver)
    {
        $approver->delete();
        return redirect()->back()->with('success', 'Data SPV/HRD berhasil dihapus');
    }

    public function bulkDestroyApprover(Request $request)
    {
        if ($request->pin !== '447747') {
            return response()->json(['success' => false, 'message' => 'PIN salah!'], 403);
        }

        $ids = $request->ids ?? [];
        if (count($ids) > 0) {
            \App\Models\Approver::whereIn('id', $ids)->delete();
        }

        return response()->json(['success' => true]);
    }
}
