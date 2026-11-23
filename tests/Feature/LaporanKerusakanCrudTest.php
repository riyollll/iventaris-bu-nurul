<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\LaporanKerusakan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LaporanKerusakanCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_report_submission_persists_report_for_barang(): void
    {
        $barang = $this->createBarang();

        $response = $this->post(route('laporan-kerusakan.store'), [
            'barang_id' => $barang->id,
            'pelapor' => 'Ibu Sari',
            'tanggal_lapor' => '2026-03-14',
            'deskripsi' => 'Kabel daya terkelupas.',
            'status' => 'dilaporkan',
        ]);

        $laporan = LaporanKerusakan::query()->firstOrFail();

        $response->assertRedirect(route('laporan-kerusakan.show', $laporan));
        $this->assertDatabaseHas('laporan_kerusakans', [
            'id' => $laporan->id,
            'barang_id' => $barang->id,
            'status' => 'dilaporkan',
        ]);
    }

    public function test_report_update_changes_status_and_description(): void
    {
        $barang = $this->createBarang();
        $laporan = $this->createReport($barang);

        $response = $this->put(route('laporan-kerusakan.update', $laporan), [
            'barang_id' => $barang->id,
            'pelapor' => 'Ibu Sari',
            'tanggal_lapor' => '2026-03-14',
            'deskripsi' => 'Sudah diganti kabel dayanya.',
            'status' => 'selesai',
        ]);

        $response->assertRedirect(route('laporan-kerusakan.show', $laporan));
        $this->assertDatabaseHas('laporan_kerusakans', [
            'id' => $laporan->id,
            'deskripsi' => 'Sudah diganti kabel dayanya.',
            'status' => 'selesai',
        ]);
    }

    public function test_report_index_filters_by_status(): void
    {
        $barang = $this->createBarang();
        $this->createReport($barang, 'Perlu diperiksa.');
        $laporanSelesai = $this->createReport($barang, 'Kabel sudah diganti.');
        $laporanSelesai->update(['status' => 'selesai']);

        $response = $this->get(route('laporan-kerusakan.index', ['status' => 'selesai']));

        $response->assertOk()
            ->assertSee('Kabel sudah diganti.')
            ->assertDontSee('Perlu diperiksa.');
    }

    public function test_report_description_is_escaped_in_detail_page(): void
    {
        $barang = $this->createBarang();
        $laporan = $this->createReport($barang, '<script>alert(1)</script>');

        $response = $this->get(route('laporan-kerusakan.show', $laporan));

        $response->assertOk()
            ->assertSee('<script>alert(1)</script>')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_report_can_be_deleted(): void
    {
        $barang = $this->createBarang();
        $laporan = $this->createReport($barang);

        $response = $this->delete(route('laporan-kerusakan.destroy', $laporan));

        $response->assertRedirect(route('laporan-kerusakan.index'));
        $this->assertDatabaseMissing('laporan_kerusakans', ['id' => $laporan->id]);
    }

    private function createBarang(): Barang
    {
        $now = now();
        $ruanganId = DB::table('ruangans')->insertGetId([
            'kode_ruangan' => 'R-'.fake()->unique()->numerify('###'),
            'nama_ruangan' => 'Ruang Guru',
            'penanggung_jawab' => 'Petugas',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $kategoriId = DB::table('kategori_barangs')->insertGetId([
            'nama_kategori' => 'Elektronik',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return Barang::create([
            'ruangan_id' => $ruanganId,
            'kategori_barang_id' => $kategoriId,
            'kode_inventaris' => 'INV-'.fake()->unique()->numerify('####'),
            'nama_barang' => 'Laptop',
            'merk' => 'Lenovo',
            'tahun_pengadaan' => now()->year,
            'kondisi' => 'baik',
        ]);
    }

    private function createReport(Barang $barang, string $deskripsi = 'Layar tidak menyala.'): LaporanKerusakan
    {
        return LaporanKerusakan::create([
            'barang_id' => $barang->id,
            'pelapor' => 'Ibu Sari',
            'tanggal_lapor' => '2026-03-14',
            'deskripsi' => $deskripsi,
            'status' => 'dilaporkan',
        ]);
    }
}
