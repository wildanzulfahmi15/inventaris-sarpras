<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Jurusan;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MapelImport;
use App\Imports\MapelPreviewImport;
use App\Exports\MapelTemplateExport;

class MapelController extends Controller
{
    // ================= LIST =================
   public function index(Request $request)
{
    $query = Mapel::with('jurusan');

    // 🔍 SEARCH NAMA MAPEL
    if ($request->filled('search')) {
        $query->where('nama_mapel', 'like', '%' . $request->search . '%');
    }

    // FILTER JENIS
    if ($request->filled('jenis')) {
        $query->where('jenis_mapel', $request->jenis);
    }

    // FILTER JURUSAN
    if ($request->filled('id_jurusan')) {
        $query->where('id_jurusan', $request->id_jurusan);
    }

    $mapel = $query
        ->orderBy('nama_mapel')
        ->paginate(10)
        ->withQueryString(); // ⭐ penting biar pagination ga reset search

    return view('sarpras.mapel.index', [
        'mapel'   => $mapel,
        'jurusan' => Jurusan::orderBy('nama_jurusan')->get()
    ]);
}


    // ================= SIMPAN =================
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel'  => 'required',
            'jenis_mapel' => 'required',
        ]);

        Mapel::create([
            'nama_mapel'  => $request->nama_mapel,
            'jenis_mapel' => $request->jenis_mapel,
            'id_jurusan'  => $request->jenis_mapel === 'jurusan'
                ? $request->id_jurusan
                : null
        ]);

        return back()->with('success', 'Mapel berhasil ditambahkan');
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel'  => 'required',
            'jenis_mapel' => 'required',
        ]);

        $mapel = Mapel::findOrFail($id);

        $mapel->update([
            'nama_mapel'  => $request->nama_mapel,
            'jenis_mapel' => $request->jenis_mapel,
            'id_jurusan'  => $request->jenis_mapel === 'jurusan'
                ? $request->id_jurusan
                : null
        ]);

        return back()->with('success', 'Mapel berhasil diperbarui');
    }

    // ================= DELETE =================
public function destroy($id)
{
    try {
        \App\Models\Mapel::where('id_mapel', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mapel berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus mapel'
        ], 500);
    }
}


    // ================= API UNTUK FORM PEMINJAMAN =================
    public function api(Request $request)
    {
        $q = $request->q;

        $query = Mapel::query();

        if ($q) {
            $query->where('nama_mapel', 'like', "%$q%");
        }

        return $query
            ->select('id_mapel', 'nama_mapel as nama')
            ->orderBy('nama_mapel')
            ->get();
    }
    public function template()
{
    return Excel::download(new MapelTemplateExport, 'template_mapel.xlsx');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new MapelImport, $request->file('file'));

    return back()->with('success', 'Mapel berhasil diimport');
}

public function preview(Request $request)
{
    if (!$request->hasFile('file')) {
        return response()->json([]);
    }

    $import = new MapelPreviewImport();
    Excel::import($import, $request->file('file'));

    return response()->json($import->rows);
}

}
