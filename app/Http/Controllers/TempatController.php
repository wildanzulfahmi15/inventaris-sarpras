<?php

namespace App\Http\Controllers;

use App\Models\Tempat;
use Illuminate\Http\Request;

class TempatController extends Controller
{
    public function byKategori(Request $request)
    {
        return Tempat::where('kategori', $request->kategori)
            ->where('aktif', 1)
            ->orderBy('nama')
            ->get();
    }
        /**
     * ========================
     * INDEX + FILTER
     * ========================
     */
    public function index(Request $request)
    {
        $query = Tempat::query();

        // 🔍 search nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // 🔍 filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 🔍 filter status aktif
        if ($request->filled('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        $tempat = $query
            ->orderBy('kategori')
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('sarpras.tempat.index', compact('tempat'));
    }

    /**
     * ========================
     * STORE
     * ========================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:100|unique:tempat,nama',
            'kategori' => 'required|in:teori,lab,lainnya',
            'aktif'    => 'required|boolean',
        ]);

        Tempat::create($validated);

        return redirect()
            ->route('sarpras.tempat.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    /**
     * ========================
     * UPDATE
     * ========================
     */
    public function update(Request $request, $id)
    {
        $tempat = Tempat::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:100|unique:tempat,nama,' . $tempat->id,
            'kategori' => 'required|in:teori,lab,lainnya',
            'aktif'    => 'required|boolean',
        ]);

        $tempat->update($validated);

        return redirect()
            ->route('sarpras.tempat.index')
            ->with('success', 'Ruangan berhasil diperbarui');
    }

    /**
     * ========================
     * DESTROY (AJAX)
     * ========================
     */
    public function destroy($id)
    {
        $tempat = Tempat::findOrFail($id);

        // ❌ CEK DIPAKAI PEMINJAMAN
        if (DB::table('peminjaman')->where('ruangan', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan sedang digunakan pada data peminjaman'
            ], 422);
        }

        $tempat->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * ========================
     * API UNTUK PEMINJAMAN
     * ========================
     */

}
