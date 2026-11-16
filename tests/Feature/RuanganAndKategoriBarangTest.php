<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RuanganAndKategoriBarangTest extends TestCase
{
    use RefreshDatabase;

    public function test_ruangan_and_kategori_barang_have_many_barangs(): void
    {
        $ruangan = Ruangan::create([
            'kode_ruangan' => 'R-001',
            'nama_ruangan' => 'Gudang',
            'penanggung_jawab' => 'Sari',
        ]);
        $kategoriBarang = KategoriBarang::create([
            'nama_kategori' => 'Elektronik',
        ]);

        $barang = Barang::create([
            'ruangan_id' => $ruangan->id,
            'kategori_barang_id' => $kategoriBarang->id,
            'kode_inventaris' => 'INV-001',
            'nama_barang' => 'Laptop',
            'merk' => 'Contoh',
            'tahun_pengadaan' => 2026,
            'kondisi' => 'baik',
        ]);

        $this->assertTrue($ruangan->barangs->contains($barang));
        $this->assertTrue($kategoriBarang->barangs->contains($barang));
    }
}
