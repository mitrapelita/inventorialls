<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->string('merk');
            $table->string('sn')->unique();
            $table->date('tanggal_masuk')->nullable();
            $table->string('kepemilikan');
            $table->string('pengguna')->nullable();
            $table->string('kontak')->nullable();
            $table->string('department')->nullable();
            $table->date('tanggal_signin')->nullable();
            $table->string('lokasi');
            $table->string('kondisi');
            $table->string('status');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
