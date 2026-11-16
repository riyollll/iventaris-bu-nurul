@extends('layouts.app')

@section('title', $laporanKerusakan ? 'Edit Laporan' : 'Buat Laporan')
@section('breadcrumb', $laporanKerusakan ? 'Edit Laporan' : 'Buat Laporan')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">LAPORAN KERUSAKAN</p>
            <h1>{{ $laporanKerusakan ? 'Edit laporan' : 'Buat laporan baru' }}</h1>
            <p class="app-page-description">Isi laporan agar kondisi barang dapat segera ditindaklanjuti.</p>
        </div>
        <a class="btn btn-subtle-neutral" href="{{ $laporanKerusakan ? route('laporan-kerusakan.show', $laporanKerusakan) : route('laporan-kerusakan.index') }}">Kembali</a>
    </section>

    <form class="card app-form-card" action="{{ $laporanKerusakan ? route('laporan-kerusakan.update', $laporanKerusakan) : route('laporan-kerusakan.store') }}" method="POST">
        @csrf
        @if ($laporanKerusakan)
            @method('PUT')
        @endif

        <div class="app-form-section">
            <div class="app-form-section-heading">
                <span class="app-form-section-number">01</span>
                <div>
                    <h2>Detail laporan</h2>
                    <p>Pilih barang dan masukkan informasi pelaporan.</p>
                </div>
            </div>
            <div class="app-form-grid">
                <div class="app-field app-field-wide">
                    <label for="barang_id">Barang <span class="app-required">*</span></label>
                    <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id" name="barang_id" required>
                        <option value="">Pilih barang yang dilaporkan</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" @selected((string) old('barang_id', request()->query('barang_id', $laporanKerusakan?->barang_id)) === (string) $barang->id)>
                                {{ $barang->nama_barang }} - {{ $barang->kode_inventaris }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="pelapor">Nama pelapor <span class="app-required">*</span></label>
                    <input class="form-control @error('pelapor') is-invalid @enderror" id="pelapor" name="pelapor" type="text" value="{{ old('pelapor', $laporanKerusakan?->pelapor) }}" placeholder="Nama lengkap" required>
                    @error('pelapor')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="tanggal_lapor">Tanggal laporan <span class="app-required">*</span></label>
                    <input class="form-control @error('tanggal_lapor') is-invalid @enderror" id="tanggal_lapor" name="tanggal_lapor" type="date" value="{{ old('tanggal_lapor', $laporanKerusakan?->tanggal_lapor?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    @error('tanggal_lapor')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field">
                    <label for="status">Status <span class="app-required">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        @foreach (['dilaporkan' => 'Dilaporkan', 'diperbaiki' => 'Diperbaiki', 'selesai' => 'Selesai'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $laporanKerusakan?->status ?? 'dilaporkan') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="app-field app-field-wide">
                    <label for="deskripsi">Deskripsi kerusakan <span class="app-required">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" maxlength="10000" placeholder="Jelaskan kondisi atau kerusakan barang..." required>{{ old('deskripsi', $laporanKerusakan?->deskripsi) }}</textarea>
                    @error('deskripsi')<span class="app-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="app-form-actions">
            <a class="btn btn-subtle-neutral" href="{{ $laporanKerusakan ? route('laporan-kerusakan.show', $laporanKerusakan) : route('laporan-kerusakan.index') }}">Batal</a>
            <button class="btn btn-primary" type="submit" @disabled($barangs === [])>
                {{ $laporanKerusakan ? 'Simpan perubahan' : 'Simpan laporan' }}
            </button>
        </div>
    </form>
@endsection
