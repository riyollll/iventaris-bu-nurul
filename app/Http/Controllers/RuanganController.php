<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('q')->toString());
        $query = Ruangan::query()->withCount('barangs');

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('kode_ruangan', 'like', "%{$search}%")
                    ->orWhere('nama_ruangan', 'like', "%{$search}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            });
        }

        return view('ruangans.index', [
            'ruangans' => $query->orderBy('nama_ruangan')->paginate(10)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('ruangans.form', ['ruangan' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_ruangan' => ['required', 'string', 'max:255', Rule::unique('ruangans', 'kode_ruangan')],
            'nama_ruangan' => ['required', 'string', 'max:255'],
            'penanggung_jawab' => ['required', 'string', 'max:255'],
        ]);

        $ruangan = Ruangan::create($data);

        return redirect()
            ->route('ruangans.show', $ruangan)
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Ruangan $ruangan): View
    {
        $ruangan->load('barangs:id,ruangan_id,kode_inventaris,nama_barang,merk,kondisi');

        return view('ruangans.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan): View
    {
        return view('ruangans.form', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan): RedirectResponse
    {
        $data = $request->validate([
            'kode_ruangan' => ['required', 'string', 'max:255', Rule::unique('ruangans', 'kode_ruangan')->ignore($ruangan)],
            'nama_ruangan' => ['required', 'string', 'max:255'],
            'penanggung_jawab' => ['required', 'string', 'max:255'],
        ]);

        $ruangan->update($data);

        return redirect()
            ->route('ruangans.show', $ruangan)
            ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan): RedirectResponse
    {
        if ($ruangan->barangs()->exists()) {
            return back()->with('error', 'Ruangan tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $ruangan->delete();

        return redirect()
            ->route('ruangans.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}
