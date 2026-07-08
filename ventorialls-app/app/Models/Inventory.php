<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis', 'merk', 'sn', 'tanggal_masuk', 'kepemilikan',
        'pengguna', 'kontak', 'department', 'team_leader',
        'tanggal_signin', 'lokasi', 'hak_bawa_pulang', 'kondisi', 'status', 'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk'    => 'date',
        'tanggal_signin'   => 'date',
        'hak_bawa_pulang'  => 'boolean',
    ];

    // Relasi ke log mutasi stok
    public function stockMutations()
    {
        return $this->hasMany(StockMutation::class);
    }
}
