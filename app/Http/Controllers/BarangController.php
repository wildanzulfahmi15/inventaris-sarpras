<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;
use App\Models\Barang;
use App\Models\Kategori;
use App\Exports\BarangTemplateExport;
use App\Imports\BarangPreviewImport;

class BarangController extends Controller
{
public function index(Request $request)
{
    $query = Barang::with('kategori');

    // SEARCH
    if ($request->filled('q')) {
        $search = $request->q;

        $query->where(function ($q) use ($search) {
            $q->where('nama_barang', 'like', "%$search%")
              ->orWhereHas('kategori', function ($k) use ($search) {
                  $k->where('nama_kategori', 'like', "%$search%");
              });
        });
    }

    $barang = $query->orderBy('nama_barang')
                    ->paginate(10)
                    ->withQueryString();

    $kategori = Kategori::all();
    // ================= STATISTIK =================
    $totalBarang = Barang::count();

    $totalStok = Barang::sum('stok');

    $totalDipinjam = Barang::all()->sum(fn ($b) => $b->stok_dipinjam);

    $totalTersedia = Barang::all()->sum(fn ($b) => $b->stok_tersedia);

    $habis = Barang::whereRaw("(stok - (
        SELECT COALESCE(SUM(jumlah),0)
        FROM detail_peminjaman
        WHERE detail_peminjaman.id_barang = barang.id_barang
        AND status_peminjaman = 'Disetujui'
        AND status_pengembalian != 'Selesai'
    )) <= 0")->count();

    return view('sarpras.barang.index', compact(
        'barang',
        'kategori',
        'totalBarang',
        'totalStok',
        'totalDipinjam',
        'totalTersedia',
        'habis'
    ));
}

public function store(Request $request)
{
    $request->validate([
        'nama_barang' => 'required',
        'stok' => 'required|integer|min:0',
        'id_kategori' => 'required|exists:kategori,id_kategori',
        'gambar' => 'nullable|image'
    ]);

    $file = null;
    if ($request->hasFile('gambar')) {
        $file = time().'.'.$request->gambar->extension();
        $request->gambar->move(public_path('gambar_barang'), $file);
    }

    Barang::create([
        'nama_barang' => $request->nama_barang,
        'stok' => $request->stok,
        'id_kategori' => $request->id_kategori,
        'gambar' => $file
    ]);

    return back()->with('success', 'Barang berhasil ditambahkan');
}


public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new BarangImport, $request->file('file'));

    return back()->with('success', 'Data barang berhasil diimport');
}


public function template()
{
    return Excel::download(
        new BarangTemplateExport,
        'template_barang.xlsx'
    );
}


public function edit($id)
{
    $barang = Barang::findOrFail($id);
    $kategori = Kategori::all();

    return view('sarpras.barang.edit', compact('barang', 'kategori'));
}
public function update(Request $request, $id)
{
    $barang = Barang::findOrFail($id);

    $request->validate([
        'nama_barang' => 'required',
        'stok' => 'required|integer|min:0',
        'id_kategori' => 'required',
        'gambar' => 'nullable|image'
    ]);

    // handle gambar baru
    if ($request->hasFile('gambar')) {
        if ($barang->gambar && file_exists(public_path('gambar_barang/'.$barang->gambar))) {
            unlink(public_path('gambar_barang/'.$barang->gambar));
        }

        $file = time().'_'.$request->gambar->getClientOriginalName();
        $request->gambar->move(public_path('gambar_barang'), $file);

        $barang->gambar = $file;
    }

    $barang->update([
        'nama_barang' => $request->nama_barang,
        'stok' => $request->stok,
        'id_kategori' => $request->id_kategori,
    ]);

    return redirect()
        ->route('sarpras.barang.index')
        ->with('success', 'Barang berhasil diperbarui');
}
public function destroy($id)
{
    $barang = Barang::withTrashed()->find($id);

    if (!$barang) {
        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ], 404);
    }

    if ($barang->stok_dipinjam > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Barang masih dipinjam, tidak bisa dihapus'
        ]);
    }

    if ($barang->gambar && file_exists(public_path('gambar_barang/'.$barang->gambar))) {
        unlink(public_path('gambar_barang/'.$barang->gambar));
    }

    // 🔥 INI KUNCI UTAMANYA
    $barang->forceDelete();

    return response()->json([
        'success' => true,
        'message' => 'Barang dan semua data peminjaman terkait berhasil dihapus permanen'
    ]);
}



public function trashed()
{
    $barang = Barang::onlyTrashed()->paginate(10);

    return view('sarpras.barang.trashed', compact('barang'));
}
public function restore($id)
{
    Barang::onlyTrashed()->findOrFail($id)->restore();
    return back()->with('success', 'Barang berhasil dipulihkan');
}

public function forceDelete($id)
{
    $barang = Barang::onlyTrashed()->findOrFail($id);

    if ($barang->gambar && file_exists(public_path('gambar_barang/'.$barang->gambar))) {
        unlink(public_path('gambar_barang/'.$barang->gambar));
    }

    $barang->forceDelete();

    return back()->with('success', 'Barang dihapus permanen');
}


public function preview(Request $request)
{
    if (!$request->hasFile('file')) {
        return response()->json([]);
    }

    $import = new BarangPreviewImport();
    Excel::import($import, $request->file('file'));

    return response()->json($import->rows);
}


}
