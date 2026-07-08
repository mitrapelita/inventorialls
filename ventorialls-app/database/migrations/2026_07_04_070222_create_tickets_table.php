<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 50)->unique();        // TKT-ST-4821, TKT-PM-8912
            $table->string('type');                              // serah_terima, peminjaman, penukaran
            $table->string('borrow_type')->nullable();          // dalam, luar (hanya untuk peminjaman)
            $table->string('status')->default('menunggu_diisi'); // menunggu_diisi, menunggu_validasi, selesai, dibatalkan
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin pembuat
            $table->timestamp('filled_at')->nullable();          // Waktu diisi karyawan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
