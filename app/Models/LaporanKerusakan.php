<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'barang_id',
    'pelapor',
    'tanggal_lapor',
    'deskripsi',
    'status',
])]
class LaporanKerusakan extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal_lapor' => 'date',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
