<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id', 'action', 'model_type', 'model_id', 'description', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // Relasi: Admin yang melakukan aksi
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Helper statik untuk mencatat log dengan mudah dari mana saja.
     *
     * @param string $action    created|updated|deleted|approved|rejected|returned
     * @param string $modelType Nama class model yang diubah (misal: 'Transaction')
     * @param int|null $modelId  ID record yang berubah
     * @param string $description Deskripsi singkat aksi yang terjadi
     * @param array|null $meta  Data tambahan (opsional)
     */
    public static function record(
        string $action,
        string $modelType,
        ?int $modelId,
        string $description,
        ?array $meta = null
    ): self {
        return self::create([
            'admin_id'    => auth()->id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $description,
            'meta'        => $meta,
        ]);
    }
}
