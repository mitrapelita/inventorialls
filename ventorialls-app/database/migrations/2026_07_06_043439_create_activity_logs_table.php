<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 50);          // created, updated, deleted, approved, rejected, returned
            $table->string('model_type', 100);     // Transaction, User, Inventory, StockMutation
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description');           // Kalimat deskripsi aksi yang terjadi
            $table->json('meta')->nullable();       // Data tambahan (doc_number, nama karyawan, dll)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
