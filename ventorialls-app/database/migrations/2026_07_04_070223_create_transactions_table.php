<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->string('doc_number', 100)->unique()->nullable(); // BAST/ST/001/2026
            $table->string('type');                                   // serah_terima, peminjaman, penukaran
            $table->string('borrow_type')->nullable();                // dalam, luar

            // Data Karyawan yang mengisi form
            $table->string('nama_pengaju');
            $table->string('department', 100);
            $table->string('no_wa', 20);

            // Status Validasi oleh Admin
            $table->string('status')->default('menunggu_validasi'); // menunggu_validasi, disetujui, ditolak
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
