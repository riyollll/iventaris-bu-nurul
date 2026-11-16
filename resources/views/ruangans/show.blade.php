@extends('layouts.app')

@section('title', $ruangan->nama_ruangan)
@section('breadcrumb', 'Detail Ruangan')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DATA MASTER / RUANGAN</p>
            <h1>{{ $ruangan->nama_ruangan }}</h1>
            <p class="app-page-description">Kode ruangan <span class="app-code">{{ $ruangan->kode_ruangan }}</span></p>
        </div>
        <div class="app-heading-actions">
            <a class="btn btn-subtle-neutral" href="{{ route('ruangans.index') }}">Kembali</a>
            <a class="btn btn-primary" href="{{ route('ruangans.edit', $ruangan) }}">Edit ruangan</a>
        </div>
    </section>

    <section class="app-detail-grid">
        <article class="card app-panel">
            <div class="app-panel-heading">
                <div>
                    <h2>Informasi ruangan</h2>
                    <p>Identitas lokasi penyimpanan barang.</p>
                </div>
            </div>
            <dl class="app-detail-list">
                <div><dt>Kode ruangan</dt><dd><span class="app-code">{{ $ruangan->kode_ruangan }}</span></dd></div>
                <div><dt>Nama ruangan</dt><dd>{{ $ruangan->nama_ruangan }}</dd></div>
                <div><dt>Penanggung jawab</dt><dd>{{ $ruangan->penanggung_jawab }}</dd></div>
                <div><dt>Jumlah barang</dt><dd>{{ $ruangan->barangs->count() }}</dd></div>
            </dl>
        </article>

        <article class="card app-panel">
            <div class="app-panel-heading">
                <div>
                    <h2>Barang di ruangan ini</h2>
                    <p>Aset yang saat ini ditempatkan di sini.</p>
                </div>
                <a class="btn btn-sm btn-subtle-neutral" href="{{ route('barangs.create') }}">Tambah barang</a>
            </div>
            @if ($ruangan->barangs->isNotEmpty())
                <div class="app-activity-list">
                    @foreach ($ruangan->barangs as $barang)
                        <a class="app-activity-item" href="{{ route('barangs.show', $barang) }}">
                            <span class="app-activity-mark" aria-hidden="true">B</span>
                            <span class="app-activity-copy">
                                <strong>{{ $barang->nama_barang }}</strong>
                                <small>{{ $barang->kode_inventaris }} &middot; {{ $barang->merk }}</small>
                            </span>
                            <x-status-badge :value="$barang->kondisi" />
                        </a>
                    @endforeach
                </div>
            @else
                <div class="app-empty-state app-empty-state-compact">
                    <strong>Belum ada barang di ruangan ini</strong>
                    <p>Barang yang ditempatkan di ruangan ini akan muncul di sini.</p>
                </div>
            @endif
        </article>
    </section>
@endsection
