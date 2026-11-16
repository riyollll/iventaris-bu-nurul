@extends('layouts.app')

@section('title', 'Data Barang')
@section('breadcrumb', 'Data Barang')

@section('content')
    <section class="app-page-heading">
        <div>
            <p class="app-eyebrow">INVENTARIS</p>
            <h1>Data barang</h1>
            <p class="app-page-description">Kelola informasi aset, lokasi, kategori, dan kondisi barang.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('barangs.create') }}">
            <span class="app-button-plus" aria-hidden="true">+</span>
            Tambah barang
        </a>
    </section>

    <section class="card app-panel">
        <form class="app-filter-bar" action="{{ route('barangs.index') }}" method="GET">
            <label class="app-search-field">
                <span class="app-search-icon" aria-hidden="true"></span>
                <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Cari nama, kode, atau merk barang..." aria-label="Cari barang">
            </label>
            <select class="form-select app-filter-select" name="kondisi" aria-label="Filter kondisi">
                <option value="">Semua kondisi</option>
                @foreach (['baik' => 'Baik', 'rusak ringan' => 'Rusak ringan', 'rusak berat' => 'Rusak berat'] as $value => $label)
                    <option value="{{ $value }}" @selected($condition === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-subtle-neutral" type="submit">Terapkan</button>
            @if ($search !== '' || $condition !== '')
                <a class="btn btn-text-neutral" href="{{ route('barangs.index') }}">Reset</a>
            @endif
        </form>

        @if ($inventoryReady && $barangs->count())
            <div class="app-table-wrap">
                <table class="table app-table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Kode inventaris</th>
                            <th>Ruangan</th>
                            <th>Kategori</th>
                            <th>Tahun</th>
                            <th>Kondisi</th>
                            <th>Laporan</th>
                            <th><span class="app-visually-hidden">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangs as $barang)
                            <tr>
                                <td>
                                    <a class="app-table-primary" href="{{ route('barangs.show', $barang) }}">{{ $barang->nama_barang }}</a>
                                    <span class="app-table-secondary">{{ $barang->merk }}</span>
                                </td>
                                <td><span class="app-code">{{ $barang->kode_inventaris }}</span></td>
                                <td>{{ $barang->nama_ruangan ?? 'Belum tersedia' }}</td>
                                <td>{{ $barang->nama_kategori ?? 'Belum tersedia' }}</td>
                                <td>{{ $barang->tahun_pengadaan }}</td>
                                <td><x-status-badge :value="$barang->kondisi" /></td>
                                <td>
                                    @if ($barang->laporan_kerusakan_count)
                                        <a class="app-count-link" href="{{ route('laporan-kerusakan.index', ['q' => $barang->kode_inventaris]) }}">{{ $barang->laporan_kerusakan_count }} laporan</a>
                                    @else
                                        <span class="app-table-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="app-row-actions">
                                        <a class="btn btn-sm btn-subtle-neutral" href="{{ route('barangs.edit', $barang) }}">Edit</a>
                                        <form action="{{ route('barangs.destroy', $barang) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
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
                <span>Menampilkan {{ $barangs->firstItem() }}-{{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang</span>
                {{ $barangs->links() }}
            </div>
        @elseif ($inventoryReady)
            <div class="app-empty-state">
                <span class="app-empty-mark" aria-hidden="true">B</span>
                <strong>{{ $search !== '' || $condition !== '' ? 'Barang tidak ditemukan' : 'Belum ada data barang' }}</strong>
                <p>{{ $search !== '' || $condition !== '' ? 'Coba ubah kata kunci atau filter kondisi.' : 'Tambahkan aset pertama untuk mulai mencatat inventaris.' }}</p>
                @if ($search === '' && $condition === '')
                    <a class="btn btn-primary btn-sm" href="{{ route('barangs.create') }}">Tambah barang</a>
                @endif
            </div>
        @else
            <div class="app-empty-state">
                <span class="app-empty-mark" aria-hidden="true">!</span>
                <strong>Tabel barang belum tersedia</strong>
                <p>Jalankan migration barang setelah migration ruangan dan kategori barang selesai.</p>
            </div>
        @endif
    </section>
@endsection
