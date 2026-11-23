<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BarangController extends Controller
{
    private const KONDISI = ['baik', 'rusak ringan', 'rusak berat'];

    public function index(Request $request): View
    {
        $inventoryReady = Schema::hasTable('barangs');
        $barangs = null;

        if ($inventoryReady) {
            $query = $this->barangQuery();
            $search = trim($request->string('q')->toString());
            $condition = $request->query('kondisi');

            if ($search !== '') {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('kode_inventaris', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%");
                });
            }

            if (in_array($condition, self::KONDISI, true)) {
                $query->where('kondisi', $condition);
            }

            $barangs = $query
                ->orderByDesc('barangs.id')
                ->paginate(10)
                ->withQueryString();

            $barangs->getCollection()->transform(function (Barang $barang): Barang {
                $barang->nama_ruangan = $barang->ruangan?->nama_ruangan;
                $barang->nama_kategori = $barang->kategoriBarang?->nama_kategori;

                return $barang;
            });
        }

        return view('barangs.index', [
            'barangs' => $barangs,
            'inventoryReady' => $inventoryReady,
            'search' => $request->string('q')->toString(),
            'condition' => $request->query('kondisi', ''),
        ]);
    }

    public function create(): View
    {
        return view('barangs.form', [
            'barang' => null,
            'ruangans' => $this->ruanganOptions(),
            'kategoriBarangs' => $this->kategoriOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $barang = Barang::create($request->validate($this->rules()));

        return redirect()
            ->route('barangs.show', $barang)
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang): View
    {
        $barang = $this->barangQuery()
            ->with('laporanKerusakan')
            ->findOrFail($barang->id);

        $barang->nama_ruangan = $barang->ruangan?->nama_ruangan;
        $barang->nama_kategori = $barang->kategoriBarang?->nama_kategori;

        return view('barangs.show', ['barang' => $barang]);
    }

    public function edit(Barang $barang): View
    {
        return view('barangs.form', [
            'barang' => $barang,
            'ruangans' => $this->ruanganOptions(),
            'kategoriBarangs' => $this->kategoriOptions(),
        ]);
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $barang->update($request->validate($this->rules($barang)));

        return redirect()
            ->route('barangs.show', $barang)
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        if ($barang->laporanKerusakan()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena masih memiliki laporan kerusakan.');
        }

        $barang->delete();

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    private function barangQuery(): Builder
    {
        return Barang::query()
            ->with(['ruangan', 'kategoriBarang'])
            ->withCount('laporanKerusakan');
    }

    private function ruanganOptions(): array
    {
        if (! Schema::hasTable('ruangans')) {
            return [];
        }

        return Ruangan::query()
            ->orderBy('nama_ruangan')
            ->get(['id', 'nama_ruangan'])
            ->all();
    }

    private function kategoriOptions(): array
    {
        if (! Schema::hasTable('kategori_barangs')) {
            return [];
        }

        return KategoriBarang::query()
            ->orderBy('nama_kategori')
            ->get(['id', 'nama_kategori'])
            ->all();
    }

    private function rules(?Barang $barang = null): array
    {
        return [
            'ruangan_id' => ['required', 'integer', Rule::exists('ruangans', 'id')],
            'kategori_barang_id' => ['required'],
            'nama_barang' => ['required'],
            'merk' => ['required', 'string', 'max:255'],
            'tahun_pengadaan' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'kondisi' => ['required', Rule::in(self::KONDISI)],
        ];
    }
}
