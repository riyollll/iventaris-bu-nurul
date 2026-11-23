<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $inventoryReady = Schema::hasTable('barangs');
        $reportsReady = Schema::hasTable('laporan_kerusakans');

        $stats = [
            'totalBarang' => $inventoryReady ? Barang::count() : 0,
            'barangBaik' => $inventoryReady ? Barang::where('kondisi', 'baik')->count() : 0,
            'barangRusak' => $inventoryReady ? Barang::whereIn('kondisi', ['rusak ringan', 'rusak berat'])->count() : 0,
            'laporanAktif' => $reportsReady ? LaporanKerusakan::whereIn('status', ['dilaporkan', 'diperbaiki'])->count() : 0,
            'laporanSelesai' => $reportsReady ? LaporanKerusakan::where('status', 'selesai')->count() : 0,
        ];

        $recentBarang = $inventoryReady
            ? Barang::query()->orderByDesc('id')->limit(5)->get()
            : collect();

        $recentLaporan = $reportsReady
            ? LaporanKerusakan::query()
                ->with('barang:id,kode_inventaris,nama_barang')
                ->orderByDesc('tanggal_lapor')
                ->orderByDesc('id')
                ->limit(5)
                ->get()
            : collect();

        return view('dashboard', [
            'stats' => $stats,
            'recentBarang' => $recentBarang,
            'recentLaporan' => $recentLaporan,
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function setupWarnings(): array
    {
        $warnings = [];

        foreach ([
            'barangs' => 'Migration barang belum dijalankan.',
            'laporan_kerusakans' => 'Migration laporan kerusakan belum dijalankan.',
            'ruangans' => 'Tabel ruangan belum tersedia; siapkan migration ruangan terlebih dahulu.',
            'kategori_barangs' => 'Tabel kategori barang belum tersedia; siapkan migration kategori barang terlebih dahulu.',
        ] as $table => $message) {
            if (! Schema::hasTable($table)) {
                $warnings[] = $message;
            }
        }

        return $warnings;
    }
}
