<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code', 'type', 'borrow_type', 'status', 'created_by', 'filled_at',
    ];

    protected $casts = [
        'filled_at' => 'datetime',
    ];

    /**
     * Generate kode tiket unik otomatis
     * Cth: TKT-ST-4821, TKT-PM-8912, TKT-PN-1234
     */
    public static function generateCode(string $type): string
    {
        $prefix = match($type) {
            'serah_terima' => 'ST',
            'peminjaman'   => 'PM',
            'penukaran'    => 'PN',
            default        => 'XX',
        };
        do {
            $code = 'TKT-' . $prefix . '-' . rand(1000, 9999);
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }

    // Relasi: Admin pembuat tiket
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi: Transaksi (hasil form yang diisi karyawan)
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    // Helper: cek apakah tiket sudah diisi
    public function isWaitingToBeFilled(): bool
    {
        return $this->status === 'menunggu_diisi';
    }
}
