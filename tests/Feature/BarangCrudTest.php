<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\LaporanKerusakan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BarangCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_inventory_navigation(): void
    {
        $response = $this->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertSee('Ringkasan inventaris')
            ->assertSee('Data Barang')
            ->assertSee('Laporan Kerusakan');
    }

    public function test_valid_barang_submission_persists_inventory_item(): void
    {
        $references = $this->createReferences();

        $response = $this->post(route('barangs.store'), [
            'ruangan_id' => $references['ruangan_id'],
            'kategori_barang_id' => $references['kategori_barang_id'],
            'kode_inventaris' => 'INV-001',
            'nama_barang' => 'Proyektor',
            'merk' => 'Epson',
            'tahun_pengadaan' => now()->year,
            'kondisi' => 'baik',
        ]);

        $barang = Barang::query()->where('kode_inventaris', 'INV-001')->firstOrFail();

        $response->assertRedirect(route('barangs.show', $barang));
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'nama_barang' => 'Proyektor',
            'ruangan_id' => $references['ruangan_id'],
        ]);
    }

    public function test_duplicate_inventory_code_returns_validation_error_without_saving(): void
    {
        $references = $this->createReferences();
        $this->createBarang($references, 'INV-DUPLICATE');

        $response = $this->from(route('barangs.create'))->post(route('barangs.store'), [
            'ruangan_id' => $references['ruangan_id'],
            'kategori_barang_id' => $references['kategori_barang_id'],
            'kode_inventaris' => 'INV-DUPLICATE',
            'nama_barang' => 'Barang kedua',
            'merk' => 'Epson',
            'tahun_pengadaan' => now()->year,
            'kondisi' => 'baik',
        ]);

        $response->assertSessionHasErrors('kode_inventaris');
        $this->assertDatabaseCount('barangs', 1);
    }

    public function test_barang_index_filters_by_inventory_code(): void
    {
        $references = $this->createReferences();
        $this->createBarang($references, 'INV-FILTER');
        $this->createBarang($references, 'INV-OTHER');

        $response = $this->get(route('barangs.index', ['q' => 'INV-FILTER']));

        $response->assertOk()
            ->assertSee('INV-FILTER')
            ->assertDontSee('INV-OTHER');
    }

    public function test_barang_update_changes_inventory_details(): void
    {
        $references = $this->createReferences();
        $barang = $this->createBarang($references, 'INV-UPDATE');

        $response = $this->put(route('barangs.update', $barang), [
            'ruangan_id' => $references['ruangan_id'],
            'kategori_barang_id' => $references['kategori_barang_id'],
            'kode_inventaris' => 'INV-UPDATE',
            'nama_barang' => 'Proyektor ruang guru',
            'merk' => 'BenQ',
            'tahun_pengadaan' => now()->year,
            'kondisi' => 'rusak ringan',
        ]);

        $response->assertRedirect(route('barangs.show', $barang));
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'nama_barang' => 'Proyektor ruang guru',
            'merk' => 'BenQ',
            'kondisi' => 'rusak ringan',
        ]);
    }

    public function test_barang_with_reports_cannot_be_deleted(): void
    {
        $references = $this->createReferences();
        $barang = $this->createBarang($references, 'INV-IN-USE');

        LaporanKerusakan::create([
            'barang_id' => $barang->id,
            'pelapor' => 'Guru',
            'tanggal_lapor' => '2026-01-10',
            'deskripsi' => 'Tidak menyala',
            'status' => 'dilaporkan',
        ]);

        $response = $this->delete(route('barangs.destroy', $barang));

        $response->assertSessionHas('error', 'Barang tidak dapat dihapus karena masih memiliki laporan kerusakan.');
        $this->assertDatabaseHas('barangs', ['id' => $barang->id]);
    }

    public function test_barang_without_reports_can_be_deleted(): void
    {
        $references = $this->createReferences();
        $barang = $this->createBarang($references, 'INV-DELETE');

        $response = $this->delete(route('barangs.destroy', $barang));

        $response->assertRedirect(route('barangs.index'));
        $this->assertDatabaseMissing('barangs', ['id' => $barang->id]);
    }

    /**
     * @return array{ruangan_id: int, kategori_barang_id: int}
     */
    private function createReferences(): array
    {
        $now = now();

        return [
            'ruangan_id' => DB::table('ruangans')->insertGetId([
                'kode_ruangan' => 'R-'.fake()->unique()->numerify('###'),
                'nama_ruangan' => 'Ruang Guru',
                'penanggung_jawab' => 'Petugas',
                'created_at' => $now,
                'updated_at' => $now,
            ]),
            'kategori_barang_id' => DB::table('kategori_barangs')->insertGetId([
                'nama_kategori' => 'Elektronik',
                'created_at' => $now,
                'updated_at' => $now,
            ]),
        ];
    }

    /**
     * @param  array{ruangan_id: int, kategori_barang_id: int}  $references
     */
    private function createBarang(array $references, string $kodeInventaris): Barang
    {
        return Barang::create([
            'ruangan_id' => $references['ruangan_id'],
            'kategori_barang_id' => $references['kategori_barang_id'],
            'kode_inventaris' => $kodeInventaris,
            'nama_barang' => 'Proyektor',
            'merk' => 'Epson',
            'tahun_pengadaan' => now()->year,
            'kondisi' => 'baik',
        ]);
    }
}
