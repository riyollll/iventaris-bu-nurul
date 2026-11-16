<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('q')->toString());
        $query = KategoriBarang::query()->withCount('barangs');

        if ($search !== '') {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        return view('kategori-barangs.index', [
            'kategoriBarangs' => $query->orderBy('nama_kategori')->paginate(10)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('kategori-barangs.form', ['kategoriBarang' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        $kategoriBarang = KategoriBarang::create($data);

        return redirect()
            ->route('kategori-barangs.show', $kategoriBarang)
            ->with('success', 'Kategori barang berhasil ditambahkan.');
    }

    public function show(KategoriBarang $kategoriBarang): View
    {
        $kategoriBarang->load('barangs:id,kategori_barang_id,kode_inventaris,nama_barang,merk,kondisi');

        return view('kategori-barangs.show', compact('kategoriBarang'));
    }

    public function edit(KategoriBarang $kategoriBarang): View
    {
        return view('kategori-barangs.form', compact('kategoriBarang'));
    }

    public function update(Request $request, KategoriBarang $kategoriBarang): RedirectResponse
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        $kategoriBarang->update($data);

        return redirect()
            ->route('kategori-barangs.show', $kategoriBarang)
            ->with('success', 'Kategori barang berhasil diperbarui.');
    }

    public function destroy(KategoriBarang $kategoriBarang): RedirectResponse
    {
        if ($kategoriBarang->barangs()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $kategoriBarang->delete();

        return redirect()
            ->route('kategori-barangs.index')
            ->with('success', 'Kategori barang berhasil dihapus.');
    }
}
