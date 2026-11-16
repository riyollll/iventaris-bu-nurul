@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('breadcrumb', 'Detail Laporan')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">LAPORAN KERUSAKAN</p>
            <h1>Detail laporan</h1>
            <p class="app-page-description">Dilaporkan pada {{ $laporanKerusakan->tanggal_lapor->format('d/m/Y') }} oleh {{ $laporanKerusakan->pelapor }}.</p>
        </div>
        <div class="app-heading-actions">
            <a class="btn btn-subtle-neutral" href="{{ route('laporan-kerusakan.index') }}">Kembali</a>
            <a class="btn btn-primary" href="{{ route('laporan-kerusakan.edit', $laporanKerusakan) }}">Edit laporan</a>
        </div>
    </section>

    <section class="app-detail-grid app-report-detail-grid">
        <article class="card app-panel">
            <div class="app-panel-heading">
                <div>
                    <h2>Informasi laporan</h2>
                    <p>Catatan kerusakan dan progres penanganan.</p>
                </div>
                <x-status-badge :value="$laporanKerusakan->status" />
            </div>
            <dl class="app-detail-list">
                <div><dt>Pelapor</dt><dd>{{ $laporanKerusakan->pelapor }}</dd></div>
                <div><dt>Tanggal laporan</dt><dd>{{ $laporanKerusakan->tanggal_lapor->format('d/m/Y') }}</dd></div>
                <div class="app-detail-description"><dt>Deskripsi</dt><dd>{{ $laporanKerusakan->deskripsi }}</dd></div>
            </dl>
        </article>

        <article class="card app-panel">
            <div class="app-panel-heading">
                <div>
                    <h2>Barang terkait</h2>
                    <p>Aset yang membutuhkan tindak lanjut.</p>
                </div>
            </div>
            @if ($laporanKerusakan->barang)
                <div class="app-related-card">
                    <span class="app-related-icon" aria-hidden="true">B</span>
                    <div>
                        <strong>{{ $laporanKerusakan->barang->nama_barang }}</strong>
                        <span class="app-code">{{ $laporanKerusakan->barang->kode_inventaris }}</span>
                    </div>
                    <a class="btn btn-sm btn-subtle-neutral" href="{{ route('barangs.show', $laporanKerusakan->barang) }}">Lihat barang</a>
                </div>
            @else
                <div class="app-empty-state app-empty-state-compact">
                    <strong>Data barang tidak tersedia</strong>
                    <p>Barang terkait mungkin sudah dihapus.</p>
                </div>
            @endif
        </article>
    </section>
@endsection
