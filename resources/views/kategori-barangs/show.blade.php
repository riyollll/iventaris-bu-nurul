@extends('layouts.app')

@section('title', $kategoriBarang->nama_kategori)
@section('breadcrumb', 'Detail Kategori')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DATA MASTER / KATEGORI</p>
            <h1>{{ $kategoriBarang->nama_kategori }}</h1>
            <p class="app-page-description">Kategori barang dengan {{ $kategoriBarang->barangs->count() }} aset.</p>
        </div>
        <div class="app-heading-actions">
            <a class="btn btn-subtle-neutral" href="{{ route('kategori-barangs.index') }}">Kembali</a>
            <a class="btn btn-primary" href="{{ route('kategori-barangs.edit', $kategoriBarang) }}">Edit kategori</a>
        </div>
    </section>

    <article class="card app-panel">
        <div class="app-panel-heading">
            <div>
                <h2>Barang dalam kategori ini</h2>
                <p>Aset inventaris yang memakai kategori {{ $kategoriBarang->nama_kategori }}.</p>
            </div>
            <a class="btn btn-sm btn-subtle-neutral" href="{{ route('barangs.create') }}">Tambah barang</a>
        </div>
        @if ($kategoriBarang->barangs->isNotEmpty())
            <div class="app-table-wrap">
                <table class="table app-table">
                    <thead>
                        <tr>
                            <th>Nama barang</th>
                            <th>Kode inventaris</th>
                            <th>Merk</th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategoriBarang->barangs as $barang)
                            <tr>
                                <td><a class="app-table-primary" href="{{ route('barangs.show', $barang) }}">{{ $barang->nama_barang }}</a></td>
                                <td><span class="app-code">{{ $barang->kode_inventaris }}</span></td>
                                <td>{{ $barang->merk }}</td>
                                <td><x-status-badge :value="$barang->kondisi" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="app-empty-state app-empty-state-compact">
                <strong>Belum ada barang dalam kategori ini</strong>
                <p>Barang yang memakai kategori ini akan muncul di sini.</p>
            </div>
        @endif
    </article>
@endsection
