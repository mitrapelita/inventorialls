<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_tl', 255)->nullable()->after('kontak');        // Nama Team Leader
            $table->string('no_ktp', 20)->nullable()->after('nama_tl');        // No KTP
            $table->text('alamat_ktp')->nullable()->after('no_ktp');           // Alamat sesuai KTP
            $table->string('domisili', 255)->nullable()->after('alamat_ktp');  // Domisili saat ini
            $table->string('ruangan', 100)->nullable()->after('domisili');     // Ruangan saat ini
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_tl', 'no_ktp', 'alamat_ktp', 'domisili', 'ruangan']);
        });
    }
};
