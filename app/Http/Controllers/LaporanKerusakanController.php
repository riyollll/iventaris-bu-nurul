<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LaporanKerusakan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class LaporanKerusakanController extends Controller
{
    private const STATUS = ['dilaporkan', 'diperbaiki', 'selesai'];

    public function index(Request $request): View
    {
        $reportsReady = Schema::hasTable('laporan_kerusakans');
        $search = trim($request->string('q')->toString());
        $status = $request->query('status');
        $laporanKerusakan = null;

        if ($reportsReady) {
            $query = LaporanKerusakan::query()
                ->with('barang:id,kode_inventaris,nama_barang');

            if ($search !== '') {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('pelapor', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('barang', function (Builder $barangQuery) use ($search): void {
                            $barangQuery->where('kode_inventaris', 'like', "%{$search}%")
                                ->orWhere('nama_barang', 'like', "%{$search}%");
                        });
                });
            }

            if (in_array($status, self::STATUS, true)) {
                $query->where('status', $status);
            }

            $laporanKerusakan = $query
                ->orderByDesc('tanggal_lapor')
                ->orderByDesc('id')
                ->paginate(10)
                ->withQueryString();
        }

        return view('laporan-kerusakan.index', [
            'laporanKerusakan' => $laporanKerusakan,
            'reportsReady' => $reportsReady,
            'search' => $request->string('q')->toString(),
            'status' => $request->query('status', ''),
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    public function create(): View
    {
        return view('laporan-kerusakan.form', [
            'laporanKerusakan' => null,
            'barangs' => $this->barangOptions(),
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->reportTablesReady()) {
            return back()
                ->withInput()
                ->withErrors(['setup' => 'Tabel barang dan laporan kerusakan harus tersedia sebelum laporan dapat disimpan.']);
        }

        $data = $request->validate([
            'barang_id' => ['required', 'integer', Rule::exists('barangs', 'id')],
            'pelapor' => ['required', 'string', 'max:255'],
            'tanggal_lapor' => ['required', 'date'],
            'deskripsi' => ['required', 'string', 'max:10000'],
            'status' => ['required', Rule::in(self::STATUS)],
        ]);

        $laporanKerusakan = LaporanKerusakan::create($data);

        return redirect()
            ->route('laporan-kerusakan.show', $laporanKerusakan)
            ->with('success', 'Laporan kerusakan berhasil ditambahkan.');
    }

    public function show(LaporanKerusakan $laporanKerusakan): View
    {
        $laporanKerusakan->load('barang:id,kode_inventaris,nama_barang');

        return view('laporan-kerusakan.show', [
            'laporanKerusakan' => $laporanKerusakan,
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    public function edit(LaporanKerusakan $laporanKerusakan): View
    {
        return view('laporan-kerusakan.form', [
            'laporanKerusakan' => $laporanKerusakan,
            'barangs' => $this->barangOptions(),
            'setupWarnings' => $this->setupWarnings(),
        ]);
    }

    public function update(Request $request, LaporanKerusakan $laporanKerusakan): RedirectResponse
    {
        if (! $this->reportTablesReady()) {
            return back()
                ->withInput()
                ->withErrors(['setup' => 'Tabel barang dan laporan kerusakan harus tersedia sebelum laporan dapat disimpan.']);
        }

        $data = $request->validate([
            'barang_id' => ['required', 'integer', Rule::exists('barangs', 'id')],
            'pelapor' => ['required', 'string', 'max:255'],
            'tanggal_lapor' => ['required', 'date'],
            'deskripsi' => ['required', 'string', 'max:10000'],
            'status' => ['required', Rule::in(self::STATUS)],
        ]);

        $laporanKerusakan->update($data);

        return redirect()
            ->route('laporan-kerusakan.show', $laporanKerusakan)
            ->with('success', 'Laporan kerusakan berhasil diperbarui.');
    }

    public function destroy(LaporanKerusakan $laporanKerusakan): RedirectResponse
    {
        $laporanKerusakan->delete();

        return redirect()
            ->route('laporan-kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil dihapus.');
    }

    /**
     * @return array<int, object>
     */
    private function barangOptions(): array
    {
        if (! Schema::hasTable('barangs')) {
            return [];
        }

        return Barang::query()
            ->orderBy('nama_barang')
            ->get(['id', 'kode_inventaris', 'nama_barang'])
            ->all();
    }

    private function reportTablesReady(): bool
    {
        return Schema::hasTable('barangs')
            && Schema::hasTable('laporan_kerusakans');
    }

    /**
     * @return array<int, string>
     */
    private function setupWarnings(): array
    {
        $warnings = [];

        foreach ([
            'barangs' => 'Migration barang belum dijalankan.',
            'laporan_kerusakans' => 'Migration laporan kerusakan belum dijalankan.',
        ] as $table => $message) {
            if (! Schema::hasTable($table)) {
                $warnings[] = $message;
            }
        }

        return $warnings;
    }
}
