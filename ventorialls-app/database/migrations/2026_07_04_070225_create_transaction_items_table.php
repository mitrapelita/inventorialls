<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('no_aset', 100);                  // Nomor aset yang diisi user
            $table->string('keterangan')->nullable();          // Normal, lecet, dll
            $table->string('sn_lama', 100)->nullable();       // Khusus Penukaran: SN barang lama yang diretur
            $table->string('foto_path', 500);                  // Path file foto bukti fisik (WAJIB)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
