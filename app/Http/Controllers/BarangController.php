<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    private const KONDISI = ['baik', 'rusak ringan', 'rusak berat'];

    public function index(Request $request): View
    {
        $inventoryReady = Schema::hasTable('barangs');
        $setupWarnings = $this->setupWarnings();
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
        }

        return view('barangs.index', [
            'barangs' => $barangs,
            'inventoryReady' => $inventoryReady,
            'search' => $request->string('q')->toString(),
            'condition' => $request->query('kondisi', ''),
            'setupWarnings' => $setupWarnings,
        ]);
    }

    public function create(): View
    {
        return view('barangs.form', [
            'barang' => null,
            'ruangans' => $this->ruanganOptions(),
            'kategoriBarangs' => $this->kategoriOptions(),
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->referenceTablesReady()) {
            return back()
                ->withInput()
                ->withErrors(['setup' => 'Tabel ruangan dan kategori barang harus tersedia sebelum barang dapat disimpan.']);
        }

        $data = $request->validate([
            'ruangan_id' => ['required', 'integer', Rule::exists('ruangans', 'id')],
            'kategori_barang_id' => ['required', 'integer', Rule::exists('kategori_barangs', 'id')],
            'kode_inventaris' => ['required', 'string', 'max:255', Rule::unique('barangs', 'kode_inventaris')],
            'nama_barang' => ['required', 'string', 'max:255'],
            'merk' => ['required', 'string', 'max:255'],
            'tahun_pengadaan' => ['required', 'integer', 'min:1900', 'max:'.now()->year],
            'kondisi' => ['required', Rule::in(self::KONDISI)],
        ]);

        $barang = Barang::create($data);

        return redirect()
            ->route('barangs.show', $barang)
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang): View
    {
        $barang = $this->barangQuery()
            ->with('laporanKerusakan')
            ->findOrFail($barang->id);

        return view('barangs.show', [
            'barang' => $barang,
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    public function edit(Barang $barang): View
    {
        return view('barangs.form', [
            'barang' => $barang,
            'ruangans' => $this->ruanganOptions(),
            'kategoriBarangs' => $this->kategoriOptions(),
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        if (! $this->referenceTablesReady()) {
            return back()
                ->withInput()
                ->withErrors(['setup' => 'Tabel ruangan dan kategori barang harus tersedia sebelum barang dapat disimpan.']);
        }

        $data = $request->validate([
            'ruangan_id' => ['required', 'integer', Rule::exists('ruangans', 'id')],
            'kategori_barang_id' => ['required', 'integer', Rule::exists('kategori_barangs', 'id')],
            'kode_inventaris' => ['required', 'string', 'max:255', Rule::unique('barangs', 'kode_inventaris')->ignore($barang)],
            'nama_barang' => ['required', 'string', 'max:255'],
            'merk' => ['required', 'string', 'max:255'],
            'tahun_pengadaan' => ['required', 'integer', 'min:1900', 'max:'.now()->year],
            'kondisi' => ['required', Rule::in(self::KONDISI)],
        ]);

        $barang->update($data);

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
        $query = Barang::query()->withCount('laporanKerusakan');

        if (Schema::hasTable('ruangans')) {
            $query->addSelect([
                'nama_ruangan' => DB::table('ruangans')
                    ->select('nama_ruangan')
                    ->whereColumn('ruangans.id', 'barangs.ruangan_id')
                    ->limit(1),
            ]);
        }

        if (Schema::hasTable('kategori_barangs')) {
            $query->addSelect([
                'nama_kategori' => DB::table('kategori_barangs')
                    ->select('nama_kategori')
                    ->whereColumn('kategori_barangs.id', 'barangs.kategori_barang_id')
                    ->limit(1),
            ]);
        }

        return $query;
    }

    private function ruanganOptions(): array
    {
        if (! Schema::hasTable('ruangans')) {
            return [];
        }

        return DB::table('ruangans')->orderBy('nama_ruangan')->get(['id', 'nama_ruangan'])->all();
    }

    private function kategoriOptions(): array
    {
        if (! Schema::hasTable('kategori_barangs')) {
            return [];
        }

        return DB::table('kategori_barangs')->orderBy('nama_kategori')->get(['id', 'nama_kategori'])->all();
    }

    private function referenceTablesReady(): bool
    {
        return Schema::hasTable('ruangans')
            && Schema::hasTable('kategori_barangs')
            && Schema::hasTable('barangs');
    }

    /**
     * @return array<int, string>
     */
    private function setupWarnings(): array
    {
        $warnings = [];

        foreach ([
            'barangs' => 'Migration barang belum dijalankan.',
            'ruangans' => 'Tabel ruangan belum tersedia; siapkan migration ruangan terlebih dahulu.',
            'kategori_barangs' => 'Tabel kategori barang belum tersedia; siapkan migration kategori barang terlebih dahulu.',
        ] as $table => $message) {
            if (! Schema::hasTable($table)) {
                $warnings[] = $message;
            }
        }

        return $warnings;
    }
}
