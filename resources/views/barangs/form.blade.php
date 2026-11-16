@extends('layouts.app')

@section('title', $barang ? 'Edit Barang' : 'Tambah Barang')
@section('breadcrumb', $barang ? 'Edit Barang' : 'Tambah Barang')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DATA BARANG</p>
            <h1>{{ $barang ? 'Edit barang' : 'Tambah barang baru' }}</h1>
            <p class="app-page-description">Lengkapi detail aset dan pastikan kode inventaris sesuai.</p>
        </div>
        <a class="btn btn-subtle-neutral" href="{{ $barang ? route('barangs.show', $barang) : route('barangs.index') }}">Kembali</a>
    </section>

    <form class="card app-form-card" action="{{ $barang ? route('barangs.update', $barang) : route('barangs.store') }}" method="POST">
        @csrf
        @if ($barang)
            @method('PUT')
        @endif

        <div class="app-form-section">
            <div class="app-form-section-heading">
                <span class="app-form-section-number">01</span>
                <div>
                    <h2>Informasi barang</h2>
                    <p>Informasi utama untuk identifikasi aset inventaris.</p>
                </div>
            </div>
            <div class="app-form-grid">
                <div class="app-field">
                    <label for="nama_barang">Nama barang <span class="app-required">*</span></label>
                    <input class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" type="text" value="{{ old('nama_barang', $barang?->nama_barang) }}" placeholder="Contoh: Proyektor kelas" required>
                    @error('nama_barang')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="kode_inventaris">Kode inventaris <span class="app-required">*</span></label>
                    <input class="form-control @error('kode_inventaris') is-invalid @enderror" id="kode_inventaris" name="kode_inventaris" type="text" value="{{ old('kode_inventaris', $barang?->kode_inventaris) }}" placeholder="Contoh: INV-2026-001" required>
                    @error('kode_inventaris')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="merk">Merk <span class="app-required">*</span></label>
                    <input class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" type="text" value="{{ old('merk', $barang?->merk) }}" placeholder="Contoh: Epson" required>
                    @error('merk')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="tahun_pengadaan">Tahun pengadaan <span class="app-required">*</span></label>
                    <input class="form-control @error('tahun_pengadaan') is-invalid @enderror" id="tahun_pengadaan" name="tahun_pengadaan" type="number" min="1900" max="{{ now()->year }}" value="{{ old('tahun_pengadaan', $barang?->tahun_pengadaan) }}" placeholder="{{ now()->year }}" required>
                    @error('tahun_pengadaan')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="app-form-section">
            <div class="app-form-section-heading">
                <span class="app-form-section-number">02</span>
                <div>
                    <h2>Penempatan dan kondisi</h2>
                    <p>Tentukan ruangan, kategori, serta kondisi barang saat ini.</p>
                </div>
            </div>
            <div class="app-form-grid">
                <div class="app-field">
                    <div class="app-field-heading">
                        <label for="ruangan_id">Ruangan <span class="app-required">*</span></label>
                        <a class="app-text-link" href="{{ route('ruangans.create') }}" target="_blank" rel="noopener">+ Tambah ruangan</a>
                    </div>
                    <select class="form-select @error('ruangan_id') is-invalid @enderror" id="ruangan_id" name="ruangan_id" required>
                        <option value="">Pilih ruangan</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" @selected((string) old('ruangan_id', $barang?->ruangan_id) === (string) $ruangan->id)>{{ $ruangan->nama_ruangan }}</option>
                        @endforeach
                    </select>
                    @error('ruangan_id')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <div class="app-field-heading">
                        <label for="kategori_barang_id">Kategori <span class="app-required">*</span></label>
                        <a class="app-text-link" href="{{ route('kategori-barangs.create') }}" target="_blank" rel="noopener">+ Tambah kategori</a>
                    </div>
                    <select class="form-select @error('kategori_barang_id') is-invalid @enderror" id="kategori_barang_id" name="kategori_barang_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($kategoriBarangs as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) old('kategori_barang_id', $barang?->kategori_barang_id) === (string) $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_barang_id')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="kondisi">Kondisi <span class="app-required">*</span></label>
                    <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                        <option value="">Pilih kondisi</option>
                        @foreach (['baik' => 'Baik', 'rusak ringan' => 'Rusak ringan', 'rusak berat' => 'Rusak berat'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('kondisi', $barang?->kondisi) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kondisi')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="app-form-actions">
            <a class="btn btn-subtle-neutral" href="{{ $barang ? route('barangs.show', $barang) : route('barangs.index') }}">Batal</a>
            <button class="btn btn-primary" type="submit" @disabled($ruangans === [] || $kategoriBarangs === [])>
                {{ $barang ? 'Simpan perubahan' : 'Simpan barang' }}
            </button>
        </div>
    </form>
@endsection
