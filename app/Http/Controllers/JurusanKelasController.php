<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JurusanImport;
use App\Imports\KelasImport;
use App\Imports\JurusanPreviewImport;
use App\Imports\KelasPreviewImport;
use App\Exports\JurusanTemplateExport;
use App\Exports\KelasTemplateExport;


class JurusanKelasController extends Controller
{
    public function index(Request $request)
{
    $searchJurusan = $request->query('search_jurusan');
    $searchKelas   = $request->query('search_kelas');

$jurusan = Jurusan::when($searchJurusan, function ($q) use ($searchJurusan) {
        $q->where('nama_jurusan', 'like', "%{$searchJurusan}%");
    })
    ->orderBy('nama_jurusan')
    ->paginate(5, ['*'], 'page_jurusan')
    ->withQueryString();

$kelas = Kelas::with('jurusan')
    ->when($searchKelas, function ($q) use ($searchKelas) {
        $q->where('nama_kelas', 'like', "%{$searchKelas}%")
          ->orWhereHas('jurusan', function ($j) use ($searchKelas) {
              $j->where('nama_jurusan', 'like', "%{$searchKelas}%");
          });
    })
    ->orderBy('id_kelas')
    ->paginate(5, ['*'], 'page_kelas')
    ->withQueryString();


    return view('sarpras.jurusan-kelas', compact('jurusan', 'kelas'));
}


public function destroyJurusan($id)
{
    $jurusan = Jurusan::withCount('kelas')->findOrFail($id);

    // ❗ cukup delete jurusan
    // kelas & relasi lain ikut terhapus via CASCADE
    $jurusan->delete();

    return response()->json([
        'success' => true,
        'message' => $jurusan->kelas_count > 0
            ? 'Jurusan dan semua kelas terkait berhasil dihapus'
            : 'Jurusan berhasil dihapus'
    ]);
}

    // ================= KELAS =================
    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'id_jurusan' => 'required'
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'id_jurusan' => $request->id_jurusan
        ]);

        return back()->with('success', 'Kelas berhasil ditambahkan');
    }

public function destroyKelas($id)
{
    Kelas::findOrFail($id)->delete();

    return response()->json([
        'success' => true,
        'message' => 'Kelas dan data terkait berhasil dihapus'
    ]);
}

    // ================= UPDATE JURUSAN =================
public function updateJurusan(Request $request, $id)
{
    $request->validate([
        'nama_jurusan' => 'required'
    ]);

    Jurusan::where('id_jurusan', $id)->update([
        'nama_jurusan' => $request->nama_jurusan
    ]);

    return back()->with('success', 'Jurusan berhasil diupdate');
}

// ================= UPDATE KELAS =================
public function updateKelas(Request $request, $id)
{
    $request->validate([
        'nama_kelas' => 'required',
        'id_jurusan' => 'required'
    ]);

    Kelas::where('id_kelas', $id)->update([
        'nama_kelas' => $request->nama_kelas,
        'id_jurusan' => $request->id_jurusan
    ]);

    return back()->with('success', 'Kelas berhasil diupdate');
}
public function previewJurusan(Request $request)
{
    $import = new JurusanPreviewImport();
    Excel::import($import, $request->file('file'));
    return response()->json($import->rows);
}

public function importJurusan(Request $request)
{
    Excel::import(new JurusanImport, $request->file('file'));
    return back()->with('success', 'Jurusan berhasil diimport');
}

public function templateJurusan()
{
    return Excel::download(new JurusanTemplateExport, 'template_jurusan.xlsx');
}
public function previewKelas(Request $request)
{
    $import = new KelasPreviewImport();
    Excel::import($import, $request->file('file'));
    return response()->json($import->rows);
}// ================= STORE JURUSAN =================
public function storeJurusan(Request $request)
{
    $request->validate([
        'nama_jurusan' => 'required|string|max:100|unique:jurusan,nama_jurusan'
    ]);

    Jurusan::create([
        'nama_jurusan' => $request->nama_jurusan
    ]);

    return back()->with('success', 'Jurusan berhasil ditambahkan');
}


public function importKelas(Request $request)
{
    Excel::import(new KelasImport, $request->file('file'));
    return back()->with('success', 'Kelas berhasil diimport');
}

public function templateKelas()
{
    return Excel::download(new KelasTemplateExport, 'template_kelas.xlsx');
}

}
