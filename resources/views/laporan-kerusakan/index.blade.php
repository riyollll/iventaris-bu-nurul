@extends('layouts.app')

@section('title', 'Laporan Kerusakan')
@section('breadcrumb', 'Laporan Kerusakan')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">TINDAK LANJUT ASET</p>
            <h1>Laporan kerusakan</h1>
            <p class="app-page-description">Catat kendala barang dan pantau progres perbaikannya.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('laporan-kerusakan.create') }}">
            <span class="app-button-plus" aria-hidden="true">+</span>
            Buat laporan
        </a>
    </section>

    <section class="card app-panel">
        <form class="app-filter-bar" action="{{ route('laporan-kerusakan.index') }}" method="GET">
            <label class="app-search-field">
                <span class="app-search-icon" aria-hidden="true"></span>
                <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Cari barang, pelapor, atau deskripsi..." aria-label="Cari laporan kerusakan">
            </label>
            <select class="form-select app-filter-select" name="status" aria-label="Filter status">
                <option value="">Semua status</option>
                @foreach (['dilaporkan' => 'Dilaporkan', 'diperbaiki' => 'Diperbaiki', 'selesai' => 'Selesai'] as $value => $label)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-subtle-neutral" type="submit">Terapkan</button>
            @if ($search !== '' || $status !== '')
                <a class="btn btn-text-neutral" href="{{ route('laporan-kerusakan.index') }}">Reset</a>
            @endif
        </form>

        @if ($reportsReady && $laporanKerusakan->count())
            <div class="app-table-wrap">
                <table class="table app-table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Pelapor</th>
                            <th>Tanggal laporan</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th><span class="app-visually-hidden">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanKerusakan as $laporan)
                            <tr>
                                <td>
                                    @if ($laporan->barang)
                                        <a class="app-table-primary" href="{{ route('barangs.show', $laporan->barang) }}">{{ $laporan->barang->nama_barang }}</a>
                                        <span class="app-table-secondary">{{ $laporan->barang->kode_inventaris }}</span>
                                    @else
                                        <span class="app-table-secondary">Barang tidak tersedia</span>
                                    @endif
                                </td>
                                <td>{{ $laporan->pelapor }}</td>
                                <td>{{ $laporan->tanggal_lapor->format('d/m/Y') }}</td>
                                <td class="app-description-cell">{{ \Illuminate\Support\Str::limit($laporan->deskripsi, 72) }}</td>
                                <td><x-status-badge :value="$laporan->status" /></td>
                                <td>
                                    <div class="app-row-actions">
                                        <a class="btn btn-sm btn-subtle-neutral" href="{{ route('laporan-kerusakan.edit', $laporan) }}">Edit</a>
                                        <form action="{{ route('laporan-kerusakan.destroy', $laporan) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-text-danger" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="app-pagination">
                <span>Menampilkan {{ $laporanKerusakan->firstItem() }}-{{ $laporanKerusakan->lastItem() }} dari {{ $laporanKerusakan->total() }} laporan</span>
                {{ $laporanKerusakan->links() }}
            </div>
        @elseif ($reportsReady)
            <div class="app-empty-state">
                <span class="app-empty-mark" aria-hidden="true">L</span>
                <strong>{{ $search !== '' || $status !== '' ? 'Laporan tidak ditemukan' : 'Belum ada laporan kerusakan' }}</strong>
                <p>{{ $search !== '' || $status !== '' ? 'Coba ubah kata kunci atau filter status.' : 'Buat laporan untuk mencatat barang yang membutuhkan perbaikan.' }}</p>
                @if ($search === '' && $status === '')
                    <a class="btn btn-primary btn-sm" href="{{ route('laporan-kerusakan.create') }}">Buat laporan</a>
                @endif
            </div>
        @else
            <div class="app-empty-state">
                <span class="app-empty-mark" aria-hidden="true">!</span>
                <strong>Tabel laporan belum tersedia</strong>
                <p>Jalankan migration barang terlebih dahulu, lalu jalankan migration laporan kerusakan.</p>
            </div>
        @endif
    </section>
@endsection
