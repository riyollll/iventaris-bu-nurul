@extends('layouts.app')

@section('title', 'Ruangan')
@section('breadcrumb', 'Ruangan')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DATA MASTER</p>
            <h1>Ruangan</h1>
            <p class="app-page-description">Atur ruangan dan penanggung jawab lokasi barang.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('ruangans.create') }}">
            <span class="app-button-plus" aria-hidden="true">+</span>
            Tambah ruangan
        </a>
    </section>

    <section class="card app-panel">
        <form class="app-filter-bar" action="{{ route('ruangans.index') }}" method="GET">
            <label class="app-search-field">
                <span class="app-search-icon" aria-hidden="true"></span>
                <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Cari nama, kode, atau penanggung jawab..." aria-label="Cari ruangan">
            </label>
            <button class="btn btn-subtle-neutral" type="submit">Cari</button>
            @if ($search !== '')
                <a class="btn btn-text-neutral" href="{{ route('ruangans.index') }}">Reset</a>
            @endif
        </form>

        @if ($ruangans->count())
            <div class="app-table-wrap">
                <table class="table app-table">
                    <thead>
                        <tr>
                            <th>Nama ruangan</th>
                            <th>Kode ruangan</th>
                            <th>Penanggung jawab</th>
                            <th>Jumlah barang</th>
                            <th><span class="app-visually-hidden">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ruangans as $ruangan)
                            <tr>
                                <td><a class="app-table-primary" href="{{ route('ruangans.show', $ruangan) }}">{{ $ruangan->nama_ruangan }}</a></td>
                                <td><span class="app-code">{{ $ruangan->kode_ruangan }}</span></td>
                                <td>{{ $ruangan->penanggung_jawab }}</td>
                                <td>{{ $ruangan->barangs_count }} barang</td>
                                <td>
                                    <div class="app-row-actions">
                                        <a class="btn btn-sm btn-subtle-neutral" href="{{ route('ruangans.edit', $ruangan) }}">Edit</a>
                                        <form action="{{ route('ruangans.destroy', $ruangan) }}" method="POST" onsubmit="return confirm('Hapus ruangan ini?')">
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
                <span>Menampilkan {{ $ruangans->firstItem() }}-{{ $ruangans->lastItem() }} dari {{ $ruangans->total() }} ruangan</span>
                {{ $ruangans->links() }}
            </div>
        @else
            <div class="app-empty-state">
                <span class="app-empty-mark" aria-hidden="true">R</span>
                <strong>{{ $search !== '' ? 'Ruangan tidak ditemukan' : 'Belum ada ruangan' }}</strong>
                <p>{{ $search !== '' ? 'Coba kata kunci yang berbeda.' : 'Tambahkan ruangan agar dapat dipilih saat mencatat barang.' }}</p>
                @if ($search === '')
                    <a class="btn btn-primary btn-sm" href="{{ route('ruangans.create') }}">Tambah ruangan</a>
                @endif
            </div>
        @endif
    </section>
@endsection
