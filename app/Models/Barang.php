<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'ruangan_id',
    'kategori_barang_id',
    'kode_inventaris',
    'nama_barang',
    'merk',
    'tahun_pengadaan',
    'kondisi',
])]
class Barang extends Model
{
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function kategoriBarang(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class);
    }

    public function laporanKerusakan(): HasMany
    {
        return $this->hasMany(LaporanKerusakan::class);
    }
}
