<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mptb.co'],
            [
                'name' => 'IT Admin',
                'email' => 'admin@mptb.co',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'id_karyawan' => 'ADM-001',
                'department' => 'IT',
                'posisi' => 'IT Administrator',
                'status_kerja' => 'Aktif',
            ]
        );

        $this->command->info('✅ Admin seeder berhasil! Email: admin@mptb.co | Password: admin123');
    }
}
