<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'inventory_id', 'jenis', 'merk', 'jumlah',
        'dari_vendor', 'tujuan', 'keterangan', 'created_by',
    ];

    // Relasi: Inventaris terkait (jika ada)
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Relasi: Admin yang mencatat mutasi
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
