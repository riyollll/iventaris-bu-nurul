<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriBarangCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created_updated_and_deleted(): void
    {
        $response = $this->post(route('kategori-barangs.store'), [
            'nama_kategori' => 'Elektronik',
        ]);

        $kategoriBarang = KategoriBarang::query()->where('nama_kategori', 'Elektronik')->firstOrFail();

        $response->assertRedirect(route('kategori-barangs.show', $kategoriBarang));
        $this->assertDatabaseHas('kategori_barangs', [
            'id' => $kategoriBarang->id,
            'nama_kategori' => 'Elektronik',
        ]);

        $response = $this->put(route('kategori-barangs.update', $kategoriBarang), [
            'nama_kategori' => 'Perangkat Elektronik',
        ]);

        $response->assertRedirect(route('kategori-barangs.show', $kategoriBarang));
        $this->assertDatabaseHas('kategori_barangs', [
            'id' => $kategoriBarang->id,
            'nama_kategori' => 'Perangkat Elektronik',
        ]);

        $this->get(route('kategori-barangs.show', $kategoriBarang))
            ->assertOk()
            ->assertSee('Perangkat Elektronik');

        $this->get(route('kategori-barangs.index'))
            ->assertOk()
            ->assertSee('Perangkat Elektronik');

        $response = $this->delete(route('kategori-barangs.destroy', $kategoriBarang));

        $response->assertRedirect(route('kategori-barangs.index'));
        $this->assertDatabaseMissing('kategori_barangs', ['id' => $kategoriBarang->id]);
    }

    public function test_category_in_use_by_barang_cannot_be_deleted(): void
    {
        $ruangan = Ruangan::create([
            'kode_ruangan' => 'R-CATEGORY',
            'nama_ruangan' => 'Ruang Guru',
            'penanggung_jawab' => 'Ibu Sari',
        ]);
        $kategoriBarang = KategoriBarang::create(['nama_kategori' => 'Elektronik']);
        $barang = Barang::create([
            'ruangan_id' => $ruangan->id,
            'kategori_barang_id' => $kategoriBarang->id,
            'kode_inventaris' => 'INV-CATEGORY-USED',
            'nama_barang' => 'Laptop',
            'merk' => 'Lenovo',
            'tahun_pengadaan' => now()->year,
            'kondisi' => 'baik',
        ]);

        $response = $this->delete(route('kategori-barangs.destroy', $kategoriBarang));

        $response->assertSessionHas('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data barang.');
        $this->assertDatabaseHas('kategori_barangs', ['id' => $kategoriBarang->id]);
        $this->assertDatabaseHas('barangs', ['id' => $barang->id]);
    }
}
