<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // Kategori barang: laptop, charger, mouse, lan_extender, headset, hp_root, audio_jack
            $table->string('kategori', 50)->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
