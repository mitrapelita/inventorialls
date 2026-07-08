<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'kategori',    // laptop, charger, mouse, lan_extender, headset, hp_root, audio_jack
        'no_aset',
        'sn_lama',     // khusus penukaran: SN aset lama yang diretur
        'alasan_penukaran',
        'penjelasan_kerusakan',
        'keterangan',
        'foto_path',
    ];

    // Relasi: Transaksi induk
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
