<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'doc_number', 'type', 'borrow_type',
        'nama_pengaju', 'department', 'no_wa',
        'status', 'validated_by', 'validated_at', 'catatan_admin',
        'spv_name', 'hrd_name', 'keterangan', 'alamat_tujuan',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    // Relasi: Tiket induk
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relasi: Admin yang memvalidasi
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Relasi: Daftar item aset dalam transaksi ini
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Generate nomor dokumen otomatis
     * Cth: BAST/ST/001/2026
     */
    public static function generateDocNumber(string $type): string
    {
        $prefix = match ($type) {
            'serah_terima' => 'BAST/ST',
            'peminjaman' => 'BAST/PM',
            'penukaran' => 'BAST/PN',
            'pengembalian' => 'BAST/KB',
            'barang_keluar' => 'BAST/BK',
            default => 'BAST/XX',
        };
        $year = now()->year;

        $latest = self::where('type', $type)
            ->whereYear('created_at', $year)
            ->whereNotNull('doc_number')
            ->orderBy('id', 'desc')
            ->first();

        if (! $latest || empty($latest->doc_number)) {
            $count = 1;
        } else {
            // Format: BAST/ST/001/2026
            $parts = explode('/', $latest->doc_number);
            if (isset($parts[2]) && is_numeric($parts[2])) {
                $count = (int) $parts[2] + 1;
            } else {
                $count = 1;
            }
        }

        return $prefix.'/'.str_pad($count, 3, '0', STR_PAD_LEFT).'/'.$year;
    }
}
