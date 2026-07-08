<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->string('type');                               // masuk, keluar
            $table->foreignId('inventory_id')->nullable()->constrained('inventories')->onDelete('set null');
            $table->string('jenis', 100);                        // Jenis barang
            $table->string('merk');                              // Merk/tipe barang
            $table->integer('jumlah')->default(1);               // Jumlah unit
            $table->string('dari_vendor')->nullable();            // Nama vendor (barang masuk)
            $table->string('tujuan')->nullable();                 // Tujuan (barang keluar)
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
