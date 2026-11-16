@extends('layouts.app')

@section('title', 'Ringkasan')
@section('breadcrumb', 'Ringkasan')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DASHBOARD</p>
            <h1>Ringkasan inventaris</h1>
            <p class="app-page-description">Pantau kondisi aset dan tindak lanjut laporan kerusakan.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('barangs.create') }}">
            <span class="app-button-plus" aria-hidden="true">+</span>
            Tambah barang
        </a>
    </section>

    <section class="app-stats-grid" aria-label="Statistik inventaris">
        <article class="card app-stat-card">
            <div class="card-body">
                <div class="app-stat-topline">
                    <span class="app-stat-label">Total barang</span>
                    <span class="app-stat-icon app-stat-icon-blue" aria-hidden="true">B</span>
                </div>
                <strong class="app-stat-value">{{ number_format($stats['totalBarang']) }}</strong>
                <span class="app-stat-caption">Barang tercatat</span>
            </div>
        </article>
        <article class="card app-stat-card">
            <div class="card-body">
                <div class="app-stat-topline">
                    <span class="app-stat-label">Kondisi baik</span>
                    <span class="app-stat-icon app-stat-icon-green" aria-hidden="true">+</span>
                </div>
                <strong class="app-stat-value">{{ number_format($stats['barangBaik']) }}</strong>
                <span class="app-stat-caption">Siap digunakan</span>
            </div>
        </article>
        <article class="card app-stat-card">
            <div class="card-body">
                <div class="app-stat-topline">
                    <span class="app-stat-label">Perlu perhatian</span>
                    <span class="app-stat-icon app-stat-icon-amber" aria-hidden="true">!</span>
                </div>
                <strong class="app-stat-value">{{ number_format($stats['barangRusak']) }}</strong>
                <span class="app-stat-caption">Barang rusak</span>
            </div>
        </article>
        <article class="card app-stat-card">
            <div class="card-body">
                <div class="app-stat-topline">
                    <span class="app-stat-label">Laporan aktif</span>
                    <span class="app-stat-icon app-stat-icon-purple" aria-hidden="true">L</span>
                </div>
                <strong class="app-stat-value">{{ number_format($stats['laporanAktif']) }}</strong>
                <span class="app-stat-caption">{{ number_format($stats['laporanSelesai']) }} laporan selesai</span>
            </div>
        </article>
    </section>

    <section class="app-dashboard-grid">
        <article class="card app-panel">
            <div class="app-panel-heading">
                <div>
                    <h2>Barang terbaru</h2>
                    <p>Data barang yang terakhir ditambahkan.</p>
                </div>
                <a class="btn btn-sm btn-subtle-neutral" href="{{ route('barangs.index') }}">Lihat semua</a>
            </div>
            @if ($recentBarang->isNotEmpty())
                <div class="app-table-wrap">
                    <table class="table app-table">
                        <thead>
                            <tr>
                                <th>Nama barang</th>
                                <th>Kode inventaris</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentBarang as $barang)
                                <tr>
                                    <td>
                                        <a class="app-table-primary" href="{{ route('barangs.show', $barang) }}">{{ $barang->nama_barang }}</a>
                                        <span class="app-table-secondary">{{ $barang->merk }}</span>
                                    </td>
                                    <td><span class="app-code">{{ $barang->kode_inventaris }}</span></td>
                                    <td><x-status-badge :value="$barang->kondisi" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="app-empty-state">
                    <span class="app-empty-mark" aria-hidden="true">B</span>
                    <strong>Belum ada data barang</strong>
                    <p>Tambahkan aset pertama untuk mulai mencatat inventaris.</p>
                    <a class="btn btn-primary btn-sm" href="{{ route('barangs.create') }}">Tambah barang</a>
                </div>
            @endif
        </article>

        <article class="card app-panel app-recent-reports">
            <div class="app-panel-heading">
                <div>
                    <h2>Laporan terbaru</h2>
                    <p>Perkembangan penanganan kerusakan.</p>
                </div>
                <a class="btn btn-sm btn-subtle-neutral" href="{{ route('laporan-kerusakan.index') }}">Lihat semua</a>
            </div>
            @if ($recentLaporan->isNotEmpty())
                <div class="app-activity-list">
                    @foreach ($recentLaporan as $laporan)
                        <a class="app-activity-item" href="{{ route('laporan-kerusakan.show', $laporan) }}">
                            <span class="app-activity-mark" aria-hidden="true">!</span>
                            <span class="app-activity-copy">
                                <strong>{{ $laporan->barang?->nama_barang ?? 'Barang tidak tersedia' }}</strong>
                                <small>{{ $laporan->pelapor }} &middot; {{ $laporan->tanggal_lapor->format('d/m/Y') }}</small>
                            </span>
                            <x-status-badge :value="$laporan->status" />
                        </a>
                    @endforeach
                </div>
            @else
                <div class="app-empty-state app-empty-state-compact">
                    <span class="app-empty-mark" aria-hidden="true">L</span>
                    <strong>Belum ada laporan</strong>
                    <p>Laporan kerusakan akan muncul di sini.</p>
                    <a class="app-text-link" href="{{ route('laporan-kerusakan.create') }}">Buat laporan <span aria-hidden="true">-&gt;</span></a>
                </div>
            @endif
        </article>
    </section>
@endsection
