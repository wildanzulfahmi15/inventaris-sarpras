<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengembalian;
use App\Models\Barang;
use App\Http\Controllers\WhatsappController;
use App\Models\DetailPeminjaman;
use App\Models\Tempat;
use App\Models\User;
use App\Notifications\PengajuanMasuk;
use App\Notifications\PengembalianMasuk;
use Kreait\Firebase\Messaging\Notification;
class GuruController extends Controller
{
public function dashboard()
{
    $guruId = Auth::user()->id_user;

    // 1️⃣ Menunggu guru menyetujui peminjaman
    $menungguPeminjaman = DetailPeminjaman::where('status_peminjaman', 'Belum')
        ->whereHas('peminjaman', function($q) use ($guruId) {
            $q->where('id_guru', $guruId);
        })
        ->count();

    // 2️⃣ Menunggu guru menyetujui pengembalian
    $menungguPengembalian = DetailPeminjaman::where('status_pengembalian', 'Menunggu Guru')
        ->whereHas('peminjaman', function($q) use ($guruId) {
            $q->where('id_guru', $guruId);
        })
        ->count();

    // 3️⃣ Barang sedang dipinjam
    $barangDipinjam = DetailPeminjaman::where('status_peminjaman', 'DisetujuI')
        ->where('status_pengembalian', '!=', 'Selesai')
        ->whereHas('peminjaman', function($q) use ($guruId) {
            $q->where('id_guru', $guruId);
        })
        ->count();

    // 4️⃣ Barang selesai dipinjam
    $barangSelesai = DetailPeminjaman::where('status_pengembalian', 'Selesai')
        ->whereHas('peminjaman', function($q) use ($guruId) {
            $q->where('id_guru', $guruId);
        })
        ->count();

    return view('guru.dashboard', compact(
        'menungguPeminjaman',
        'menungguPengembalian',
        'barangDipinjam',
        'barangSelesai'
    ));
}


public function guruIndex()
{
    $guruId = Auth::user()->id_user;

    // Ambil semua detail barang yang menunggu guru
$detail = DetailPeminjaman::with([
    'barang',
    'peminjaman.siswa.kelasRelasi',
    'peminjaman.guru',
    'peminjaman.mapel',
    'peminjaman.tempat', // 🔥 TAMBAH INI
])

        ->where('status_peminjaman', 'Menunggu Guru')
        ->whereHas('peminjaman', function($q) use ($guruId) {
            $q->where('id_guru', $guruId);
        })
        ->get();

    return view('guru.konfirmasi', compact('detail'));
}



public function guruSetuju($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    if ($detail->peminjaman->id_guru != Auth::user()->id_user) {
        return back()->with('info', 'Tidak berwenang.');
    }

    $barang = $detail->barang;
    $peminjaman = $detail->peminjaman;

    // ❌ STOK HABIS
    if ($barang->stok < $detail->jumlah) {
        $detail->status_peminjaman = 'Stok Habis';
        $detail->save();
        
        // 🔥 CEK SEMUA DETAIL
        $adaYangLolos = DetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)
            ->whereIn('status_peminjaman', ['Menunggu Sarpras', 'Disetujui'])
            ->exists();

        if (!$adaYangLolos) {
            $peminjaman->status = 'Ditolak';
            $peminjaman->save();
        }
        WhatsappController::guruStokHabis($detail);
        return back()->with(
            'error',
            'Stok '.$barang->nama_barang.' habis. Item ini otomatis ditolak.'
        );
    }

    // ✅ STOK CUKUP
    $barang->decrement('stok', $detail->jumlah);

    $detail->status_peminjaman = 'Menunggu Sarpras';
    $detail->save();

    // 🔥 UPDATE STATUS HEADER
    $peminjaman->status = 'Diajukan';
    $peminjaman->save();
    
// ✅ PERBAIKI di GuruController::guruSetuju
$p = $detail->peminjaman;
$p->load('details.barang', 'guru', 'siswa');

$sarpras = User::where('role', 'sarpras')->get();
foreach ($sarpras as $admin) {
    $admin->notify(new PengajuanMasuk($p));
}

    return response()->json([
    'message' => 'Peminjaman disetujui guru.'
]);
}




public function guruTolak($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    if ($detail->peminjaman->id_guru != Auth::user()->id_user) {
        return back()->with('info', 'Tidak berwenang.');
    }

    // Tandai detail sebagai ditolak
    $detail->status_peminjaman = 'Ditolak Guru';
    $detail->save();

    // Cek: apakah SEMUA barang di peminjaman ini ditolak?
    $peminjaman = $detail->peminjaman;

    $adaYangTidakDitolak = DetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)
        ->where('status_peminjaman', '!=', 'Ditolak Guru')
        ->exists();

    if (!$adaYangTidakDitolak) {
        // Semua detail ditolak → ubah status header
        $peminjaman->status = 'Ditolak';
        $peminjaman->save();
    }
    WhatsappController::guruTolakPeminjaman($detail);
    return response()->json([
    'message' => 'Peminjaman ditolak guru.'
]);
}

public function pengembalian()
{
    $guruId = Auth::user()->id_user;

    $detail = DetailPeminjaman::with([
        'barang',
        'peminjaman.siswa.kelasRelasi',
        'peminjaman.guru',
        'peminjaman.mapel',
        'peminjaman.tempat',
    ])
    ->where('status_peminjaman', 'Disetujui')
    ->whereIn('status_pengembalian', ['Belum', 'Ditolak Sarpras'])
    ->whereHas('peminjaman', function($q) use ($guruId) {
        $q->where('id_guru', $guruId);
    })
    ->get();

    return view('guru.pengembalian', compact('detail'));
}


public function setujuPengembalian($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    // hanya guru yg bersangkutan
    if ($detail->peminjaman->id_guru != Auth::user()->id_user) {
        return back()->with('info', 'Tidak berwenang.');
    }

    // set status detail
    $detail->status_pengembalian = 'Menunggu Sarpras';
    
    $detail->save();
    $detail->load('barang', 'peminjaman.guru', 'peminjaman.siswa');

$sarpras = User::where('role', 'sarpras')->get();

foreach ($sarpras as $admin) {
    $admin->notify(new PengembalianMasuk($detail));
}
    return response()->json([
        'message' => 'Pengembalian disetujui guru.'
    ]);
}

public function tolakPengembalian($id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    if ($detail->peminjaman->id_guru != Auth::user()->id_user) {
        return back()->with('info', 'Tidak berwenang.');
    }

    $detail->status_pengembalian = 'Ditolak Guru';
    $detail->save();
    return response()->json([
        'message' => 'Pengembalian ditolak guru.'
    ]);
}

public function kembalikan(Request $request, $id)
{
    $detail = DetailPeminjaman::findOrFail($id);

    if ($request->foto) {
        $image = str_replace('data:image/jpeg;base64,', '', $request->foto);
        $image = str_replace(' ', '+', $image);
        $imageName = 'pengembalian_' . time() . '.png';

        \Storage::disk('public')->put(
            'pengembalian/' . $imageName,
            base64_decode($image)
        );

        $detail->foto_pengembalian = 'pengembalian/' . $imageName;
    }

    $detail->status_pengembalian = 'Menunggu Sarpras';
    $detail->save();

    // 🔥 ambil peminjaman
    $peminjaman = $detail->peminjaman;
    $peminjaman->load('guru', 'details');

    $sarpras = \App\Models\User::where('role', 'sarpras')->get();

    // =========================
    // ✅ LARAVEL NOTIF (PER BARANG)
    // =========================
    foreach ($sarpras as $admin) {
        $admin->notify(new \App\Notifications\PengembalianMasuk($detail));
    }


// cek apakah ini barang pertama yg dikembalikan
$count = DetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)
    ->where('status_pengembalian', 'Menunggu Sarpras')
    ->count();

if ($count <= 1) {

    $tokens = $sarpras->pluck('fcm_token')
        ->filter()
        ->unique()
        ->toArray();

    if (!empty($tokens)) {

        $messaging = app('firebase.messaging');

        // 🔥 pakai DATA ONLY (biar gak double notif)
foreach ($tokens as $token) {

    $message = \Kreait\Firebase\Messaging\CloudMessage::new()
        ->withData([
            'title' => 'Pengajuan Pengembalian',
            'body'  => ($peminjaman->guru->nama ?? 'Guru') .
                       ' mengembalikan ' .
                       $peminjaman->details->count() .
                       ' barang',
            'url'   => '/sarpras/pengembalian'
        ])
        ->toToken($token);

    $messaging->send($message);
}
    }
}

    return response()->json([
        'message' => 'Berhasil dikembalikan'
    ]);
}
}
    