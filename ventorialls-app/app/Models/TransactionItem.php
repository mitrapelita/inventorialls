<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'inventory_id', // ditambahkan untuk fitur tracking barang
        'kategori',    // laptop, charger, mouse, lan_extender, headset, hp_root, audio_jack
        'no_aset',
        'jumlah',
        'sn_lama',     // khusus penukaran: SN aset lama yang diretur
        'alasan_penukaran',
        'penjelasan_kerusakan',
        'keterangan',
        'is_returned', // ditambahkan untuk fitur tracking barang kembali
        'foto_path',
    ];

    // Relasi: Transaksi induk
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi: Master Inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }
}
