@extends('layouts.app')

@section('title', $barang->nama_barang)
@section('breadcrumb', 'Detail Barang')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DETAIL INVENTARIS</p>
            <h1>{{ $barang->nama_barang }}</h1>
            <p class="app-page-description">Kode inventaris <span class="app-code">{{ $barang->kode_inventaris }}</span></p>
        </div>
        <div class="app-heading-actions">
            <a class="btn btn-subtle-neutral" href="{{ route('barangs.index') }}">Kembali</a>
            <a class="btn btn-primary" href="{{ route('barangs.edit', $barang) }}">Edit barang</a>
        </div>
    </section>

    <section class="app-detail-grid">
        <article class="card app-panel">
            <div class="app-panel-heading">
                <div>
                    <h2>Informasi barang</h2>
                    <p>Detail dan penempatan aset.</p>
                </div>
                <x-status-badge :value="$barang->kondisi" />
            </div>
            <dl class="app-detail-list">
                <div><dt>Nama barang</dt><dd>{{ $barang->nama_barang }}</dd></div>
                <div><dt>Kode inventaris</dt><dd><span class="app-code">{{ $barang->kode_inventaris }}</span></dd></div>
                <div><dt>Merk</dt><dd>{{ $barang->merk }}</dd></div>
                <div><dt>Tahun pengadaan</dt><dd>{{ $barang->tahun_pengadaan }}</dd></div>
                <div><dt>Ruangan</dt><dd>{{ $barang->nama_ruangan ?? 'Belum tersedia' }}</dd></div>
                <div><dt>Kategori</dt><dd>{{ $barang->nama_kategori ?? 'Belum tersedia' }}</dd></div>
                <div><dt>Jumlah laporan</dt><dd>{{ $barang->laporanKerusakan->count() }}</dd></div>
            </dl>
        </article>

        <article class="card app-panel">
            <div class="app-panel-heading">
                <div>
                    <h2>Riwayat kerusakan</h2>
                    <p>Laporan yang terkait dengan barang ini.</p>
                </div>
                <a class="btn btn-sm btn-subtle-neutral" href="{{ route('laporan-kerusakan.create', ['barang_id' => $barang->id]) }}">Buat laporan</a>
            </div>
            @if ($barang->laporanKerusakan->isNotEmpty())
                <div class="app-activity-list">
                    @foreach ($barang->laporanKerusakan->sortByDesc('tanggal_lapor') as $laporan)
                        <a class="app-activity-item" href="{{ route('laporan-kerusakan.show', $laporan) }}">
                            <span class="app-activity-mark" aria-hidden="true">!</span>
                            <span class="app-activity-copy">
                                <strong>{{ $laporan->pelapor }}</strong>
                                <small>{{ $laporan->tanggal_lapor->format('d/m/Y') }}</small>
                            </span>
                            <x-status-badge :value="$laporan->status" />
                        </a>
                    @endforeach
                </div>
            @else
                <div class="app-empty-state app-empty-state-compact">
                    <span class="app-empty-mark" aria-hidden="true">L</span>
                    <strong>Belum ada laporan kerusakan</strong>
                    <p>Buat laporan jika barang ini membutuhkan perbaikan.</p>
                </div>
            @endif
        </article>
    </section>
@endsection
