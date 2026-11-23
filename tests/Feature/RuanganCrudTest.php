<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RuanganCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_can_be_created_and_updated(): void
    {
        $response = $this->post(route('ruangans.store'), [
            'kode_ruangan' => 'R-001',
            'nama_ruangan' => 'Ruang Guru',
            'penanggung_jawab' => 'Ibu Sari',
        ]);

        $ruangan = Ruangan::query()->where('kode_ruangan', 'R-001')->firstOrFail();

        $response->assertRedirect(route('ruangans.show', $ruangan));
        $this->assertDatabaseHas('ruangans', [
            'id' => $ruangan->id,
            'nama_ruangan' => 'Ruang Guru',
        ]);

        $response = $this->put(route('ruangans.update', $ruangan), [
            'kode_ruangan' => 'R-001',
            'nama_ruangan' => 'Ruang Guru Baru',
            'penanggung_jawab' => 'Pak Budi',
        ]);

        $response->assertRedirect(route('ruangans.show', $ruangan));
        $this->assertDatabaseHas('ruangans', [
            'id' => $ruangan->id,
            'nama_ruangan' => 'Ruang Guru Baru',
            'penanggung_jawab' => 'Pak Budi',
        ]);

        $this->get(route('ruangans.show', $ruangan))
            ->assertOk()
            ->assertSee('Ruang Guru Baru')
            ->assertSee('Pak Budi');

        $this->get(route('ruangans.index'))
            ->assertOk()
            ->assertSee('R-001');
    }

    public function test_duplicate_room_code_returns_validation_error(): void
    {
        Ruangan::create([
            'kode_ruangan' => 'R-DUPLICATE',
            'nama_ruangan' => 'Ruang Lama',
            'penanggung_jawab' => 'Petugas',
        ]);

        $response = $this->from(route('ruangans.create'))->post(route('ruangans.store'), [
            'kode_ruangan' => 'R-DUPLICATE',
            'nama_ruangan' => 'Ruang Baru',
            'penanggung_jawab' => 'Petugas',
        ]);

        $response->assertSessionHasErrors('kode_ruangan');
        $this->assertDatabaseCount('ruangans', 1);
    }

    public function test_room_in_use_by_barang_cannot_be_deleted(): void
    {
        $ruangan = Ruangan::create([
            'kode_ruangan' => 'R-USED',
            'nama_ruangan' => 'Ruang Guru',
            'penanggung_jawab' => 'Ibu Sari',
        ]);
        $kategoriBarang = KategoriBarang::create(['nama_kategori' => 'Elektronik']);
        $barang = Barang::create([
            'ruangan_id' => $ruangan->id,
            'kategori_barang_id' => $kategoriBarang->id,
            'kode_inventaris' => 'INV-ROOM-USED',
            'nama_barang' => 'Laptop',
            'merk' => 'Lenovo',
            'tahun_pengadaan' => now()->year,
            'kondisi' => 'baik',
        ]);

        $response = $this->delete(route('ruangans.destroy', $ruangan));

        $response->assertSessionHas('error', 'Ruangan tidak dapat dihapus karena masih digunakan oleh data barang.');
        $this->assertDatabaseHas('ruangans', ['id' => $ruangan->id]);
        $this->assertDatabaseHas('barangs', ['id' => $barang->id]);
    }
}
