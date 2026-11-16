<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nama_kategori',
])]
class KategoriBarang extends Model
{
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
