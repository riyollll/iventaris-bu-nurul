@extends('layouts.app')

@section('title', $kategoriBarang ? 'Edit Kategori' : 'Tambah Kategori')
@section('breadcrumb', $kategoriBarang ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DATA MASTER / KATEGORI</p>
            <h1>{{ $kategoriBarang ? 'Edit kategori barang' : 'Tambah kategori barang' }}</h1>
            <p class="app-page-description">Gunakan nama kategori yang mudah dikenali saat mencatat barang.</p>
        </div>
        <a class="btn btn-subtle-neutral" href="{{ $kategoriBarang ? route('kategori-barangs.show', $kategoriBarang) : route('kategori-barangs.index') }}">Kembali</a>
    </section>

    <form class="card app-form-card" action="{{ $kategoriBarang ? route('kategori-barangs.update', $kategoriBarang) : route('kategori-barangs.store') }}" method="POST">
        @csrf
        @if ($kategoriBarang)
            @method('PUT')
        @endif

        <div class="app-form-section">
            <div class="app-form-section-heading">
                <span class="app-form-section-number">01</span>
                <div>
                    <h2>Informasi kategori</h2>
                    <p>Nama kategori akan tersedia sebagai pilihan pada form barang.</p>
                </div>
            </div>
            <div class="app-form-grid">
                <div class="app-field app-field-wide">
                    <label for="nama_kategori">Nama kategori <span class="app-required">*</span></label>
                    <input class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori" name="nama_kategori" type="text" value="{{ old('nama_kategori', $kategoriBarang?->nama_kategori) }}" placeholder="Contoh: Elektronik" required>
                    @error('nama_kategori')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="app-form-actions">
            <a class="btn btn-subtle-neutral" href="{{ $kategoriBarang ? route('kategori-barangs.show', $kategoriBarang) : route('kategori-barangs.index') }}">Batal</a>
            <button class="btn btn-primary" type="submit">{{ $kategoriBarang ? 'Simpan perubahan' : 'Simpan kategori' }}</button>
        </div>
    </form>
@endsection
