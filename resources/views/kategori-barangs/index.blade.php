@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('breadcrumb', 'Kategori Barang')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">DATA MASTER</p>
            <h1>Kategori barang</h1>
            <p class="app-page-description">Kelompokkan aset agar data inventaris lebih mudah dikelola.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('kategori-barangs.create') }}">
            <span class="app-button-plus" aria-hidden="true">+</span>
            Tambah kategori
        </a>
    </section>

    <section class="card app-panel">
        <form class="app-filter-bar" action="{{ route('kategori-barangs.index') }}" method="GET">
            <label class="app-search-field">
                <span class="app-search-icon" aria-hidden="true"></span>
                <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Cari nama kategori..." aria-label="Cari kategori barang">
            </label>
            <button class="btn btn-subtle-neutral" type="submit">Cari</button>
            @if ($search !== '')
                <a class="btn btn-text-neutral" href="{{ route('kategori-barangs.index') }}">Reset</a>
            @endif
        </form>

        @if ($kategoriBarangs->count())
            <div class="app-table-wrap">
                <table class="table app-table">
                    <thead>
                        <tr>
                            <th>Nama kategori</th>
                            <th>Jumlah barang</th>
                            <th><span class="app-visually-hidden">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategoriBarangs as $kategoriBarang)
                            <tr>
                                <td><a class="app-table-primary" href="{{ route('kategori-barangs.show', $kategoriBarang) }}">{{ $kategoriBarang->nama_kategori }}</a></td>
                                <td>{{ $kategoriBarang->barangs_count }} barang</td>
                                <td>
                                    <div class="app-row-actions">
                                        <a class="btn btn-sm btn-subtle-neutral" href="{{ route('kategori-barangs.edit', $kategoriBarang) }}">Edit</a>
                                        <form action="{{ route('kategori-barangs.destroy', $kategoriBarang) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
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
                <span>Menampilkan {{ $kategoriBarangs->firstItem() }}-{{ $kategoriBarangs->lastItem() }} dari {{ $kategoriBarangs->total() }} kategori</span>
                {{ $kategoriBarangs->links() }}
            </div>
        @else
            <div class="app-empty-state">
                <span class="app-empty-mark" aria-hidden="true">K</span>
                <strong>{{ $search !== '' ? 'Kategori tidak ditemukan' : 'Belum ada kategori barang' }}</strong>
                <p>{{ $search !== '' ? 'Coba kata kunci yang berbeda.' : 'Tambahkan kategori agar dapat dipilih saat mencatat barang.' }}</p>
                @if ($search === '')
                    <a class="btn btn-primary btn-sm" href="{{ route('kategori-barangs.create') }}">Tambah kategori</a>
                @endif
            </div>
        @endif
    </section>
@endsection
