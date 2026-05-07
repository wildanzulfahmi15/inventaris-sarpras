<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();
        return view('kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    // AJAX (dari modal)
public function storeAjax(Request $request)
{
    $request->validate([
        'nama_kategori' => 'required|unique:kategori,nama_kategori',
        'keterangan' => 'nullable|string',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $filename = null;

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('img/kategori'), $filename);
    }

    $kategori = Kategori::create([
        'nama_kategori' => $request->nama_kategori,
        'keterangan'   => $request->keterangan, // ✅ FIX
        'gambar'       => $filename
    ]);

    return response()->json([
        'success' => true,
        'id' => $kategori->id_kategori,
        'nama' => $kategori->nama_kategori,
        'keterangan' => $kategori->keterangan,
        'gambar' => $kategori->gambar
    ]);
}



    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $id . ',id_kategori'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Kategori berhasil diupdate');
    }

public function destroy($id)
{
    $kategori = Kategori::withCount('barang')->findOrFail($id);

    // hapus gambar kategori
    if ($kategori->gambar && file_exists(public_path('img/kategori/'.$kategori->gambar))) {
        unlink(public_path('img/kategori/'.$kategori->gambar));
    }

    // barang otomatis ikut terhapus (ON DELETE CASCADE)
    $kategori->delete();

    return response()->json([
        'success' => true,
        'message' => $kategori->barang_count > 0
            ? 'Kategori dan semua barang terkait berhasil dihapus'
            : 'Kategori berhasil dihapus'
    ]);
}



public function updateAjax(Request $request, $id)
{
    $kategori = Kategori::where('id_kategori', $id)->firstOrFail();

    $request->validate([
        'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $id . ',id_kategori',
        'keterangan' => 'nullable|string',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    if ($request->hasFile('gambar')) {
        if ($kategori->gambar && file_exists(public_path('img/kategori/' . $kategori->gambar))) {
            unlink(public_path('img/kategori/' . $kategori->gambar));
        }

        $file = $request->file('gambar');
        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('img/kategori'), $filename);

        $kategori->gambar = $filename;
    }

    $kategori->nama_kategori = $request->nama_kategori;
    $kategori->keterangan   = $request->keterangan; // ✅ FIX
    $kategori->save();

    return response()->json([
        'success' => true,
        'message' => 'Kategori berhasil diperbarui',
        'data' => [
            'id' => $kategori->id_kategori,
            'nama' => $kategori->nama_kategori,
            'keterangan' => $kategori->keterangan,
            'gambar' => $kategori->gambar
        ]
    ]);
}

}
