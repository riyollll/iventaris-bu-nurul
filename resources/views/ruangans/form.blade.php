@extends('layouts.app')

@section('title', $ruangan ? 'Edit Ruangan' : 'Tambah Ruangan')
@section('breadcrumb', $ruangan ? 'Edit Ruangan' : 'Tambah Ruangan')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DATA MASTER / RUANGAN</p>
            <h1>{{ $ruangan ? 'Edit ruangan' : 'Tambah ruangan' }}</h1>
            <p class="app-page-description">Masukkan identitas ruangan dan petugas yang bertanggung jawab.</p>
        </div>
        <a class="btn btn-subtle-neutral" href="{{ $ruangan ? route('ruangans.show', $ruangan) : route('ruangans.index') }}">Kembali</a>
    </section>

    <form class="card app-form-card" action="{{ $ruangan ? route('ruangans.update', $ruangan) : route('ruangans.store') }}" method="POST">
        @csrf
        @if ($ruangan)
            @method('PUT')
        @endif

        <div class="app-form-section">
            <div class="app-form-section-heading">
                <span class="app-form-section-number">01</span>
                <div>
                    <h2>Informasi ruangan</h2>
                    <p>Kode ruangan harus unik dan dapat digunakan untuk identifikasi.</p>
                </div>
            </div>
            <div class="app-form-grid">
                <div class="app-field">
                    <label for="kode_ruangan">Kode ruangan <span class="app-required">*</span></label>
                    <input class="form-control @error('kode_ruangan') is-invalid @enderror" id="kode_ruangan" name="kode_ruangan" type="text" value="{{ old('kode_ruangan', $ruangan?->kode_ruangan) }}" placeholder="Contoh: R-001" required>
                    @error('kode_ruangan')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="nama_ruangan">Nama ruangan <span class="app-required">*</span></label>
                    <input class="form-control @error('nama_ruangan') is-invalid @enderror" id="nama_ruangan" name="nama_ruangan" type="text" value="{{ old('nama_ruangan', $ruangan?->nama_ruangan) }}" placeholder="Contoh: Ruang Guru" required>
                    @error('nama_ruangan')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field app-field-wide">
                    <label for="penanggung_jawab">Penanggung jawab <span class="app-required">*</span></label>
                    <input class="form-control @error('penanggung_jawab') is-invalid @enderror" id="penanggung_jawab" name="penanggung_jawab" type="text" value="{{ old('penanggung_jawab', $ruangan?->penanggung_jawab) }}" placeholder="Nama penanggung jawab ruangan" required>
                    @error('penanggung_jawab')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="app-form-actions">
            <a class="btn btn-subtle-neutral" href="{{ $ruangan ? route('ruangans.show', $ruangan) : route('ruangans.index') }}">Batal</a>
            <button class="btn btn-primary" type="submit">{{ $ruangan ? 'Simpan perubahan' : 'Simpan ruangan' }}</button>
        </div>
    </form>
@endsection
