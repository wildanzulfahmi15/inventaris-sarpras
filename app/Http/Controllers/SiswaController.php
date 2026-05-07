<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaPreviewImport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiswaController extends Controller
{



public function index(Request $request)
{
    $query = Siswa::with('kelasRelasi');

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nis', 'like', '%' . $request->search . '%')
              ->orWhere('nama', 'like', '%' . $request->search . '%')
              ->orWhereHas('kelasRelasi', function ($k) use ($request) {
                  $k->where('nama_kelas', 'like', '%' . $request->search . '%');
              });
        });
    }

    return view('sarpras.siswa.index', [
        'siswa' => Siswa::with('kelasRelasi')
            ->orderBy('id_siswa', 'desc')
            ->paginate(10),
        'kelas' => Kelas::orderBy('nama_kelas')->get()
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'nama' => 'required',
            'id_kelas' => 'required'
        ]);

        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'id_kelas' => $request->id_kelas,
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nis' => 'required',
            'nama' => 'required',
            'id_kelas' => 'required'
        ]);

        Siswa::where('id_siswa', $id)->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'id_kelas' => $request->id_kelas,
        ]);

        return back()->with('success', 'Data siswa berhasil diupdate');
    }

public function destroy($id)
{
    try {
        Siswa::where('id_siswa', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus siswa',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function search(Request $request)
{
    $q = $request->q;

    $siswa = Siswa::with('kelasRelasi')
        ->where('nis', 'like', "%$q%")
        ->orWhere('nama', 'like', "%$q%")
        ->orWhereHas('kelasRelasi', function ($query) use ($q) {
            $query->where('nama_kelas', 'like', "%$q%");
        })
        ->orderBy('id_siswa', 'desc')
        ->limit(20)
        ->get();

    return response()->json($siswa);
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls'
    ]);

    $rows = Excel::toCollection(null, $request->file('file'))[0];

    $inserted = 0;
    $failed = 0;

    foreach ($rows->skip(1) as $row) {
        $nis = trim($row[0] ?? '');
        $nama = trim($row[1] ?? '');
        $kelasNama = trim($row[2] ?? '');

        if (!$nis || !$nama || !$kelasNama) {
            $failed++;
            continue;
        }

        if (\App\Models\Siswa::where('nis', $nis)->exists()) {
            $failed++;
            continue;
        }

        $kelas = \App\Models\Kelas::where('nama_kelas', $kelasNama)->first();
        if (!$kelas) {
            $failed++;
            continue;
        }

        \App\Models\Siswa::create([
            'nis' => $nis,
            'nama' => $nama,
            'id_kelas' => $kelas->id_kelas,
        ]);

        $inserted++;
    }

    return response()->json([
        'success' => true,
        'inserted' => $inserted,
        'failed' => $failed
    ]);
}

public function template()
{
    return Excel::download(
        new SiswaTemplateExport,
        'template_siswa.xlsx'
    );
}



public function preview(Request $request)
{
    if (!$request->hasFile('file')) {
        return response()->json([]);
    }

    $import = new SiswaPreviewImport();
    Excel::import($import, $request->file('file'));

    return response()->json($import->rows);
}
}
