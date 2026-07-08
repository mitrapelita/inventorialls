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
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->string('alasan_penukaran', 50)->nullable()->after('keterangan');
            $table->text('penjelasan_kerusakan')->nullable()->after('alasan_penukaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn(['alasan_penukaran', 'penjelasan_kerusakan']);
        });
    }
};
