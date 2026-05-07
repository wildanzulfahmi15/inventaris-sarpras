<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPeminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\WhatsappController;
use App\Notifications\PengajuanDisetujui;
use App\Notifications\PengajuanDitolak;
use App\Notifications\PengembalianDisetujui;
use App\Notifications\PengembalianDitolak;
use Kreait\Firebase\Messaging\Notification;
class SarprasController extends Controller
{
    public function index()
    {
        $menungguPeminjaman = DetailPeminjaman::where('status_peminjaman', 'Menunggu Sarpras')->count();
$menungguPengembalian = DetailPeminjaman::where('status_pengembalian', 'Menunggu Sarpras')->count();
$sedangDipinjam = DetailPeminjaman::where('status_peminjaman', 'Disetujui Sarpras')->where('status_pengembalian', 'Belum')->count();
$selesai = DetailPeminjaman::where('status_pengembalian', 'Selesai')->count();

return view('sarpras.dashboard', compact(
    'menungguPeminjaman',
    'menungguPengembalian',
    'sedangDipinjam',
    'selesai'
));
    }

public function peminjaman()
{
    $detail = DetailPeminjaman::with([
            'barang',
            'peminjaman.siswa',
            'peminjaman.guru',
            'peminjaman.mapel'
        ])
        ->where('status_peminjaman', 'Menunggu Sarpras')
        ->get();

    return view('sarpras.peminjaman', compact('detail'));
}


public function konfirmasi($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    // Setujui barang
    $detail->status_peminjaman = 'Disetujui';
    $detail->save();

    // jika SEMUA detail di peminjaman sudah Disetujui → update header peminjaman
    $p = $detail->peminjaman;

    $waiting = $p->detail()
        ->whereIn('status_peminjaman', ['Menunggu Guru', 'Menunggu Sarpras'])
        ->count();

    if ($waiting == 0) {
        $p->status = 'Dipinjam';  // seluruh barang sudah ACC
        $p->save();
    }
    WhatsappController::sarprasSetujuPeminjaman($detail);
$guru = $detail->peminjaman->guru;

$detail->load('barang');

$guru->notify(new PengajuanDisetujui($detail));

$token = $guru->fcm_token;

if ($token) {

    $messaging = app('firebase.messaging');

$message = \Kreait\Firebase\Messaging\CloudMessage::new()
    ->withData([
        'title' => 'Peminjaman Disetujui',
        'body'  => 'Peminjaman ' .
                   optional($detail->barang)->nama_barang .
                   ' disetujui',
        'url'   => '/guru/riwayat'
    ])
    ->toToken($token);

$messaging->send($message);
}

    return response()->json([
    'message' => 'Peminjaman berhasil dikonfirmasi Sarpras.'
]);
}


public function tolak($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    // 🔥 KEMBALIKAN STOK
    $detail->barang->increment('stok', $detail->jumlah);
    $detail->status_peminjaman = 'Ditolak Sarpras';
    $detail->save();

    // jika semua barang ditolak → header peminjaman
    $p = $detail->peminjaman;

    $valid = $p->detail()->where('status_peminjaman', '!=', 'Ditolak Sarpras')->count();

    if ($valid == 0) {
        $p->status = 'Ditolak';
        $p->save();
    }
    WhatsappController::sarprasTolakPeminjaman($detail);

$guru = $detail->peminjaman->guru;

$detail->load(['barang', 'peminjaman']);
$guru->notify(new PengajuanDitolak($detail));

$token = $guru->fcm_token;

if ($token) {

    $messaging = app('firebase.messaging');

$message = \Kreait\Firebase\Messaging\CloudMessage::new()
    ->withData([
        'title' => 'Peminjaman Ditolak',
        'body'  => 'Peminjaman ' .
                   optional($detail->barang)->nama_barang .
                   ' ditolak',
        'url'   => '/guru/riwayat'
    ])
    ->toToken($token);

$messaging->send($message);
}
return response()->json([
    'message' => 'Peminjaman berhasil ditolak.'
]);
}



public function pengembalian()
{
    $detail = DetailPeminjaman::with([
        'barang',
        'peminjaman.siswa',
        'peminjaman.guru',
        'peminjaman.mapel'
    ])
    ->where('status_pengembalian', 'Menunggu Sarpras')
    ->get();

    return view('sarpras.pengembalian', compact('detail'));
}

private function updateStatusHeader($peminjaman)
{
    $masihProses = $peminjaman->detail()
        ->where(function($q){
            $q->whereNotIn('status_pengembalian', ['Selesai'])
              ->whereNotIn('status_peminjaman', ['Stok Habis', 'Ditolak Guru', 'Ditolak Sarpras']);
        })
        ->count();

    if ($masihProses == 0) {
        $peminjaman->status = 'Dikembalikan';
        $peminjaman->save();
    }
}

public function setuju($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    // Set detail sebagai selesai
    $detail->status_pengembalian = 'Selesai';
        if (!$detail->tanggal_pengembalian) {
        $detail->tanggal_pengembalian = now();
    }


    // Tambahkan stok karena barang sudah benar-benar kembali
    $detail->barang->increment('stok', $detail->jumlah);

    $detail->save();

    // Cek apakah SEMUA detail selesai
    $p = $detail->peminjaman;
    

    $this->updateStatusHeader($p);
        WhatsappController::sarprasSetujuPengembalian($detail);

$guru = $detail->peminjaman->guru;

$detail->load('barang');

$guru->notify(new PengembalianDisetujui($detail));

$token = $guru->fcm_token;

if ($token) {

    $messaging = app('firebase.messaging');

$message = \Kreait\Firebase\Messaging\CloudMessage::new()
    ->withData([
        'title' => 'Pengembalian Disetujui',
        'body'  => optional($detail->barang)->nama_barang .
                   ' sudah diterima',
        'url'   => '/guru/riwayat'
    ])
    ->toToken($token);

$messaging->send($message);
}

    return response()->json([
    'message' => 'Pengembalian disetujui dan selesai.'
]);
}


public function tolakPengembalian($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    $detail->status_pengembalian = 'Ditolak Sarpras';
    $detail->save();

    WhatsappController::sarprasTolakPengembalian($detail);

    $guru = $detail->peminjaman->guru;

    $p = $detail->peminjaman; // ✅ INI YANG KURANG

$detail->load('barang');
$guru->notify(new PengembalianDitolak($detail));

$token = $guru->fcm_token;

if ($token) {

    $messaging = app('firebase.messaging');

$message = \Kreait\Firebase\Messaging\CloudMessage::new()
    ->withData([
        'title' => 'Pengembalian Ditolak',
        'body'  => optional($detail->barang)->nama_barang .
                   ' ditolak',
        'url'   => '/guru/riwayat'
    ])
    ->toToken($token);

$messaging->send($message);

    $messaging->send($message);
}

    return response()->json([
        'message' => 'Pengembalian ditolak Sarpras.'
    ]);
}



public function riwayat(Request $request)
{
    $query = DetailPeminjaman::with([
        'peminjaman.siswa',
        'peminjaman.guru',
        'barang'
    ])->orderBy('created_at', 'desc');

    // ================= FILTER TANGGAL =================
    if ($request->filled('tanggal_mulai')) {
        $query->whereHas('peminjaman', function ($q) use ($request) {
            $q->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        });
    }

    if ($request->filled('tanggal_selesai')) {
        $query->whereHas('peminjaman', function ($q) use ($request) {
            $q->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        });
    }

    // ================= FILTER STATUS =================
    if ($request->filled('status_transaksi')) {
        $query->whereHas('peminjaman', function ($q) use ($request) {
            $q->where('status', $request->status_transaksi);
        });
    }

    // ================= QUICK FILTER =================
    if ($request->filter === 'diajukan') {
    $query->whereHas('peminjaman', fn ($q) =>
        $q->where('status', 'Diajukan')
    );
}
    if ($request->filter === 'berlangsung') {
        $query->whereHas('peminjaman', fn ($q) =>
            $q->where('status', 'Dipinjam')
        );
    }

    if ($request->filter === 'selesai') {
        $query->whereHas('peminjaman', fn ($q) =>
            $q->where('status', 'Dikembalikan')
        );
    }

    // ================= SEARCH =================
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('barang', fn ($b) =>
                $b->where('nama_barang', 'like', "%$search%")
            )
            ->orWhereHas('peminjaman.siswa', fn ($s) =>
                $s->where('nama', 'like', "%$search%")
            )
            ->orWhereHas('peminjaman.guru', fn ($g) =>
                $g->where('nama', 'like', "%$search%")
            );
        });
    }

    // ================= PAGINATION =================
    $detail = $query->paginate(10)->withQueryString();

    return view('sarpras.riwayat', compact('detail'));
}





public function riwayatPdf(Request $request)
{
    $query = DetailPeminjaman::with([
        'barang',
        'peminjaman.siswa',
        'peminjaman.guru'
    ]);

    if ($request->filled(['tanggal_mulai','tanggal_selesai'])) {
        $query->whereHas('peminjaman', function ($q) use ($request) {
            $q->whereBetween('tanggal_pinjam', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        });
    }

if ($request->filled('status_transaksi')) {
    $query->whereHas('peminjaman', function ($q) use ($request) {
        $q->where('status', $request->status_transaksi);
    });
}

    if ($request->filled('kelas')) {
        $query->whereHas('peminjaman.siswa', function ($q) use ($request) {
            $q->where('kelas', 'like', '%'.$request->kelas.'%');
        });
    }

    $detail = $query
        ->orderBy('created_at', 'asc')
        ->get();

    $pdf = Pdf::loadView('sarpras.riwayat_pdf', compact('detail'))
        ->setPaper('A4','landscape');

    return $pdf->stream('laporan-riwayat-peminjaman.pdf');
}

public function hapusDetailRiwayat($id_detail)
{
    DB::transaction(function () use ($id_detail) {

        $detail = DetailPeminjaman::with(['barang','peminjaman'])
            ->findOrFail($id_detail);

        $peminjaman = $detail->peminjaman;

        // ============================
        // CEK APAKAH STOK PERNAH BERKURANG
        // ============================
        $stokPernahBerkurang = in_array(
            $detail->status_peminjaman,
            ['Menunggu Sarpras', 'Disetujui']
        );

        // ============================
        // CEK APAKAH STOK SUDAH DIKEMBALIKAN
        // ============================
        $stokSudahDikembalikan = ($detail->status_pengembalian === 'Selesai');

        // ============================
        // TAMBAH STOK HANYA JIKA PERLU
        // ============================
        if ($stokPernahBerkurang && !$stokSudahDikembalikan) {
            $detail->barang->increment('stok', $detail->jumlah);
        }

        // ============================
        // HAPUS DETAIL
        // ============================
        $detail->delete();

        // ============================
        // CEK HEADER PEMINJAMAN
        // ============================
        $sisaDetail = DetailPeminjaman::where(
            'id_peminjaman',
            $peminjaman->id_peminjaman
        )->count();

        if ($sisaDetail === 0) {
            $peminjaman->delete();
        }
    });

        return response()->json([
        'message' => 'Barang berhasil dihapus'
    ], 200);
}




public function setujuiDetail($id_detail)
{
    $detail = DetailPeminjaman::with(['barang','peminjaman'])->findOrFail($id_detail);
    $barang = $detail->barang;
    $peminjaman = $detail->peminjaman;
    if ($detail->status_peminjaman == 'Menunggu Guru') {

        if ($barang->stok < $detail->jumlah) {
            $detail->status_peminjaman = 'Stok Habis';
            $detail->save();

            $peminjaman->status = 'Ditolak';
            $peminjaman->save();

            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $barang->decrement('stok', $detail->jumlah);

        $detail->status_peminjaman = 'Disetujui';
        $detail->save();

        $peminjaman->status = 'Dipinjam';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman disetujui.');
    }

    // =====================
    // 2️⃣ SETUJUI PENGEMBALIAN  ✅ INI YANG KURANG
    // =====================
    if ($detail->status_pengembalian == 'Menunggu Guru') {

        $detail->status_pengembalian = 'Selesai';
        $detail->save();    

        // balikin stok
        $barang->increment('stok', $detail->jumlah);

        // cek semua detail
        $belum = $peminjaman->detail()
            ->where('status_pengembalian', '!=', 'Selesai')
            ->count();

        $this->updateStatusHeader($peminjaman);

                return back()->with('success', 'Pengembalian disetujui.');
            }

    return back()->with('info', 'Tidak ada aksi.');
}

public function riwayatJson(Request $request)
{
    $query = DetailPeminjaman::with([
        'peminjaman.siswa.kelas',   // ⬅️ pakai relasi FINAL
        'peminjaman.guru',
        'peminjaman.mapel',
        'peminjaman.tempat',        // 🔥 INI KUNCI RUANGAN
        'barang'
    ])->orderBy('created_at', 'desc');
// ================= FILTER STATUS =================
if ($request->filled('status_transaksi')) {
    $query->whereHas('peminjaman', function ($q) use ($request) {
        $q->where('status', $request->status_transaksi);
    });
}

// ================= QUICK FILTER =================
if ($request->filter === 'diajukan') {
    $query->whereHas('peminjaman', fn ($q) =>
        $q->where('status', 'Diajukan')
    );
}

if ($request->filter === 'berlangsung') {
    $query->whereHas('peminjaman', fn ($q) =>
        $q->where('status', 'Dipinjam')
    );
}

if ($request->filter === 'selesai') {
    $query->whereHas('peminjaman', fn ($q) =>
        $q->where('status', 'Dikembalikan')
    );
}
    // SEARCH
    if ($request->filled('search')) {
        $s = $request->search;

        $query->where(function ($q) use ($s) {
            $q->whereHas('peminjaman.siswa', fn ($x) =>
                $x->where('nama', 'like', "%$s%")
                  ->orWhere('nis', 'like', "%$s%")
            )
            ->orWhereHas('peminjaman.guru', fn ($x) =>
                $x->where('nama', 'like', "%$s%")
            )
            ->orWhereHas('peminjaman.mapel', fn ($x) =>
                $x->where('nama_mapel', 'like', "%$s%")
            )
            ->orWhereHas('barang', fn ($x) =>
                $x->where('nama_barang', 'like', "%$s%")
            );
        });
    }

    $data = $query->paginate(10);

        return response()->json(
        $query->paginate(10)->withQueryString()
    );
}

}
