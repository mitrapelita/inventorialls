<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('karyawan')->after('password'); // 'admin' or 'karyawan'
            $table->string('id_karyawan', 50)->unique()->nullable()->after('role');
            $table->string('department', 100)->nullable()->after('id_karyawan');
            $table->string('posisi', 100)->nullable()->after('department');
            $table->string('kontak', 20)->nullable()->after('posisi');
            $table->text('alamat')->nullable()->after('kontak');
            $table->string('status_kerja')->default('Aktif')->after('alamat'); // 'Aktif' or 'Resign'
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'id_karyawan', 'department', 'posisi', 'kontak', 'alamat', 'status_kerja']);
        });
    }
};
