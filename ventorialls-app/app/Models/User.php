<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Prunable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'id_karyawan', 'department', 'posisi',
        'kontak', 'alamat', 'status_kerja',
        // Detail Serah Terima
        'nama_tl', 'no_ktp', 'alamat_ktp', 'domisili', 'ruangan',
        // Snapshot aset sebelum dihapus (untuk restore)
        'aset_tersimpan',
    ];

    protected $casts = [
        'aset_tersimpan' => 'array',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relasi: Tiket yang dibuat oleh Admin ini
    public function ticketsCreated()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    // Relasi: Transaksi yang divalidasi oleh Admin ini
    public function validatedTransactions()
    {
        return $this->hasMany(Transaction::class, 'validated_by');
    }

    /**
     * Dapatkan daftar aset aktif yang sedang digunakan oleh karyawan ini.
     * Lookup berdasarkan nama (pengguna) di tabel inventories.
     */
    public function asetAktif()
    {
        return Inventory::where('pengguna', $this->name)->where('status', 'Aktif')->get();
    }

    // Relasi: Inventaris yang digunakan oleh Karyawan ini
    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'pengguna', 'name');
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subDays(30));
    }
}
