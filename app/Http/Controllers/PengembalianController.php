<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPeminjaman;

class PengembalianController extends Controller
{
    /**
     * Menampilkan form input NIS dan tabel peminjaman
     */

public function form()
{
    return view('pengembalian', ['peminjaman' => null]);
}
public function cari(Request $request)
{
    if (!$request->filled('nis')) {
        return redirect()
            ->route('pengembalian')
            ->with('error', 'NIS tidak boleh kosong.');
    }

    $peminjaman = Peminjaman::with([
            'detail' => function ($q) {
                $q->where('status_peminjaman', 'Disetujui')
                  ->whereIn('status_pengembalian', [
                      'Belum',
                      'Menunggu Guru',
                      'Menunggu Sarpras',
                  'Ditolak Sarpras',
                  'Ditolak Guru',
                  ])
                  ->with('barang');
            },
            'siswa',
            'guru',
            'mapel'
        ])
        ->whereHas('siswa', fn($q) => $q->where('nis', $request->nis))
        ->whereHas('detail', function ($q) {
            $q->where('status_peminjaman', 'Disetujui')
              ->whereIn('status_pengembalian', [
                  'Belum',
                  'Menunggu Guru',
                  'Menunggu Sarpras',
                  'Ditolak Sarpras',
                  'Ditolak Guru'
              ]);
        })
        ->get();

    return view('pengembalian', compact('peminjaman'));
}


public function kembalikan($id)
{
    \Log::info("Kembalikan dipanggil untuk ID $id");

    try {
        $detail = DetailPeminjaman::findOrFail($id);

        $detail->status_pengembalian = 'Menunggu Guru';
        $detail->tanggal_pengembalian = now();
        $detail->save();

        \Log::info("Status detail updated");

        $pinjam = $detail->peminjaman;

        $waiting = $pinjam->detail()
            ->whereNotIn('status_pengembalian', ['Diajukan', 'Selesai'])
            ->count();

        if ($waiting == 0) {
            $pinjam->status = 'Diajukan';
            $pinjam->save();
        }

        return response()->json([
            'success' => true,
            'status' => 'Diajukan'
        ]);

    } catch (\Exception $e) {
        \Log::error("ERROR KEMBALIKAN: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}




}
