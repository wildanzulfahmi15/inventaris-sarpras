<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Models\Siswa;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Mapel;
use App\Models\DetailPeminjaman;
use App\Http\Controllers\WhatsappController;
use App\Models\Tempat;
use Kreait\Firebase\Messaging\Notification;

class PeminjamanController extends Controller

{
    // Ambil guru dengan search
    public function getGuru(Request $request)
    {
        $search = $request->input('q');

        $guru = User::where('role', 'guru')
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->get(['id_user', 'nama']);

        return response()->json($guru);
    }

public function pilihBarang(Request $request)
{
    if ($request->from !== 'form') {
        session()->forget('barangDipilih');
    }

    $barangDipilih = session('barangDipilih', []);

    // ambil semua kategori beserta barangnya
    $kategori = Kategori::with('barang')->get();

    return view('guru.pilih_barang', [
        'kategori' => $kategori,
        'barangDipilih' => $barangDipilih
    ]);
}



public function riwayat(Request $request)
{
    $filter = $request->filter;
    $search = strtolower($request->search);

    $query = Peminjaman::with(['siswa', 'guru', 'details.barang'])
        ->orderBy('created_at', 'desc');

    // FILTER STATUS
    if ($filter == 'berlangsung') {
        $query->where('status', 'Dipinjam');
    } elseif ($filter == 'selesai') {
        $query->where('status', 'Dikembalikan');
    }


    $riwayat = $query->get();

    return view('sarpras.riwayat', compact('riwayat', 'filter', 'search'));
}

public function riwayatGuru(Request $request)
{
    $filter = $request->filter;
    $search = strtolower($request->search);

    $guruId = auth()->user()->id_user;

    $query = Peminjaman::with(['siswa', 'details.barang'])
        ->where('id_guru', $guruId)
        ->orderBy('created_at', 'desc');

    // FILTER STATUS
    if ($filter == 'berlangsung') {
        $query->where('status', 'Dipinjam');
    } elseif ($filter == 'selesai') {
        $query->where('status', 'Dikembalikan');
    }



    $riwayat = $query->get();

    return view('guru.riwayat', compact('riwayat', 'filter', 'search'));
}
public function riwayatGuruJson(Request $request)
{
    $guruId  = auth()->user()->id_user;
    $filter  = $request->filter;
    $search  = strtolower($request->search);

    $query = DetailPeminjaman::with([
        'barang',
        'peminjaman.siswa.kelasRelasi',
        'peminjaman'
    ])
    ->whereHas('peminjaman', function ($q) use ($guruId) {
        $q->where('id_guru', $guruId);
    })
    ->orderByDesc('id_detail');

    // ── FILTER ──
    if ($filter === 'berlangsung') {
        $query->whereHas('peminjaman', fn($q) => $q->where('status', 'Dipinjam'));
    } elseif ($filter === 'selesai') {
        $query->whereHas('peminjaman', fn($q) => $q->where('status', 'Dikembalikan'));
    }

    // ── SEARCH ──
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('peminjaman.siswa', function ($sq) use ($search) {
                $sq->whereRaw('LOWER(nama) LIKE ?', ["%$search%"])
                   ->orWhereRaw('LOWER(nis) LIKE ?',  ["%$search%"]);
            })
            ->orWhereHas('barang', function ($sq) use ($search) {
                $sq->whereRaw('LOWER(nama_barang) LIKE ?', ["%$search%"]);
            });
        });
    }

    $data = $query->paginate(10)->through(function ($d) {
        return [
            'id_detail_peminjaman' => $d->id_detail_peminjaman,
            'jumlah'               => $d->jumlah,
            'status_peminjaman'    => $d->status_peminjaman,
            'status_pengembalian'  => $d->status_pengembalian,
            'tanggal_pengembalian' => $d->tanggal_pengembalian,
            'barang' => $d->barang ? [
                'nama_barang' => $d->barang->nama_barang,
            ] : null,
            'peminjaman' => $d->peminjaman ? [
                'tanggal_pinjam' => $d->peminjaman->tanggal_pinjam,
                'status'         => $d->peminjaman->status,
                'siswa' => $d->peminjaman->siswa ? [
                    'nama'  => $d->peminjaman->siswa->nama,
                    'nis'   => $d->peminjaman->siswa->nis,
                    'kelas' => $d->peminjaman->siswa->kelasRelasi ? [
                        'nama_kelas' => $d->peminjaman->siswa->kelasRelasi->nama_kelas,
                    ] : null,
                ] : null,
            ] : null,
        ];
    });

    return response()->json($data);
}
public function hapusRiwayat($id)
{
    $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);

    foreach ($peminjaman->details as $detail) {

        // Jika barang BELUM dikembalikan, kembalikan stoknya
        if ($detail->status_pengembalian == 'Belum') {

            $barang = $detail->barang;
            $barang->stok += $detail->jumlah; 
            $barang->save();
        }

        // Jika sedang "Menunggu Guru" dalam pengembalian → juga dianggap belum kembali
        if ($detail->status_pengembalian == 'Menunggu Guru' ||
            $detail->status_pengembalian == 'Menunggu Sarpras') {

            $barang = $detail->barang;
            $barang->stok += $detail->jumlah;
            $barang->save();
        }
    }

    // Hapus detail peminjaman
    $peminjaman->details()->delete();

    // Hapus transaksi utama
    $peminjaman->delete();

    return redirect()->back()->with('success', 'Riwayat berhasil dihapus dan stok dikembalikan.');
}




// 2. Terima barang dan masuk ke form peminjaman
public function formBarang(Request $request)
{
    session(['barangDipilih' => $request->barang]);

    return redirect()->route('peminjaman.form');
}



    public function getMapel(Request $request)
    {
        $q = $request->input('q');

        $query = Mapel::query();

        if ($q && trim($q) !== '') {
            $query->where('nama_mapel', 'like', '%' . $q . '%');
        }

        $mapel = $query->select('id_mapel', 'nama_mapel as nama')
                      ->orderBy('nama_mapel', 'asc')
                      ->get();

        return response()->json($mapel);
    }

public function form(Request $request)
{
    $barangDipilih = session('barangDipilih');

    if (!$barangDipilih || count($barangDipilih) === 0) {
        return redirect()
            ->route('peminjaman.kategori')
            ->with('error', 'Silakan pilih barang terlebih dahulu.');
    }

    return view('guru.peminjaman_form', [
        'barangDipilih' => $barangDipilih
    ]);
}


// API SISWA + JURUSAN
public function getSiswa(Request $request)
{
    $siswa = Siswa::with('kelasRelasi.jurusan')
        ->where('nis', $request->nis)
        ->first();

    if (!$siswa) {
        return response()->json(null, 404);
    }

    return response()->json([
        'nama' => $siswa->nama,
        'kelas' => $siswa->kelasRelasi->nama_kelas ?? $siswa->kelas,
        'id_jurusan' => $siswa->kelasRelasi->id_jurusan ?? null,
    ]);
}

// API GURU
public function getGuruByJenis(Request $request)
{
    try {
        if ($request->jenis === 'jurusan') {
            $data = User::where('role','guru')
                ->where('id_jurusan', $request->id_jurusan)
                ->get(['id_user','nama']);
        } else {
            $data = User::where('role','guru')->get(['id_user','nama']);
        }

        return response()->json($data);

    } catch (\Throwable $e) {
        return response()->json([], 200); // ⬅️ PENTING
    }
}


// API MAPEL
public function getMapelByJenis(Request $request)
{
    try {
        if ($request->jenis === 'jurusan') {
            $data = Mapel::where('jenis_mapel','jurusan')
                ->where('id_jurusan', $request->id_jurusan)
                ->get(['id_mapel','nama_mapel as nama']);
        } elseif ($request->jenis === 'umum') {
            $data = Mapel::where('jenis_mapel','umum')
                ->get(['id_mapel','nama_mapel as nama']);
        } else {
            $data = Mapel::where('jenis_mapel','ekskul')
                ->get(['id_mapel','nama_mapel as nama']);
        }

        return response()->json($data);

    } catch (\Throwable $e) {
        return response()->json([], 200); // ⬅️ PENTING
    }
}




public function kategori()
{
    session()->forget('barangDipilih');

    $kategori = Kategori::all();

    return view('kategori', compact('kategori'));
}




    // Search barang berdasarkan kategori + input ketik
    public function getBarang(Request $request)
    {
        $query = Barang::where('kategori', $request->kategori);

        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }

        return $query->get(['id_barang', 'nama_barang', 'stok']);
    }

    // Store peminjaman
public function store(Request $request)
{

// di dalam method store(Request $request) paling atas
Log::info('peminjaman.store called', [
    'method' => request()->method(),
    'url'    => request()->fullUrl(),
    'inputs' => $request->all(),
]);

$request->validate([
    'nis'        => 'required',
    'id_mapel'   => 'required|integer|exists:mapel,id_mapel',
    'id_guru' => 'required|exists:user,id_user',
'tempat_id' => 'required|exists:tempat,id',

    'barang'     => 'required|array',
    'barang.*.nama_barang' => 'required',
    'barang.*.jumlah' => 'required|integer|min:1',
]);




    // 2️⃣ AMBIL SISWA (WAJIB PALING ATAS)
    $siswa = Siswa::with('kelasRelasi')
        ->where('nis', $request->nis)
        ->first();

    if (!$siswa) {
        return back()->withErrors(['nis' => 'Siswa tidak ditemukan']);
    }

    // 3️⃣ VALIDASI GURU
    $guru = User::where('id_user', $request->id_guru)->first();

    if (!$guru) {
        return back()->withErrors([
            'id_guru' => 'Guru tidak ditemukan'
        ]);
    }

    // 4️⃣ AMBIL MAPEL (EXISTS SUDAH DI-VALIDATE)
    $mapel = Mapel::find($request->id_mapel);

    if (!$mapel) {
        return back()->withErrors(['mapel' => 'Mata pelajaran tidak valid']);
    }

    // 5️⃣ KUNCI MAPEL JURUSAN (ANTI NGASAL 🔒)
    $jurusanSiswa = $siswa->kelasRelasi->id_jurusan ?? null;

    if ($mapel->jenis_mapel === 'jurusan') {

        if (!$jurusanSiswa || $mapel->id_jurusan !== $jurusanSiswa) {
            return back()->withErrors([
                'mapel' => 'Mapel jurusan tidak sesuai dengan jurusan siswa'
            ]);
        }

        if ($guru->id_jurusan !== $jurusanSiswa) {
            return back()->withErrors([
                'namaGuru' => 'Guru tidak sesuai dengan jurusan siswa'
            ]);
        }
    }

// ===============================
// VALIDASI NOMOR WHATSAPP
// ===============================

$noWa = null;

if ($request->filled('no_siswa')) {
    $noWa = preg_replace('/^0/', '62', $request->no_siswa);

    try {
        $cek = Http::timeout(5)->post('http://localhost:3001/check-wa', [
            'number' => $noWa
        ])->json();

        if (!isset($cek['valid']) || $cek['valid'] !== true) {
            return back()->withInput()->with(
                'error',
                'Nomor WhatsApp tidak terdaftar atau tidak aktif.'
            );
        }
    } catch (\Throwable $e) {
    Log::warning('Bot WA mati, skip validasi', [
        'no_wa' => $noWa
    ]);
    // ❗ LANJUTKAN PROSES, JANGAN RETURN
}

}

$tempat = Tempat::find($request->tempat_id);

if (!$tempat) {
    return back()->withErrors(['tempat' => 'Tempat tidak valid']);
}

$ruanganId = $tempat->id;


$fotoPinjam = null;

if ($request->foto_guru) {

    $image = str_replace('data:image/png;base64,', '', $request->foto_guru);
    $image = str_replace(' ', '+', $image);

    $fotoPinjam = 'pinjam_' . time() . '.png';

    \Storage::disk('public')->put(
        'peminjaman/' . $fotoPinjam,
        base64_decode($image)
    );
}
    DB::beginTransaction();
    try {
        // 1) Buat header peminjaman
$peminjaman = Peminjaman::create([
    'id_siswa' => $siswa->id_siswa,
    'id_guru'  => $guru->id_user,
    'id_mapel' => $request->id_mapel,
    'tanggal_pinjam' => now(),
    'ruangan'  => $ruanganId, // ✅ ID TEMPAT
    'no_wa'    => $noWa,
    'foto_pinjam' => $fotoPinjam,
    'alasan'  => $request->deskripsi,
    'status'  => 'Diajukan',
]);

foreach ($request->barang as $item) {

    $barang = Barang::find($item['id_barang']);

    if (!$barang) continue;

    $barang->stok = max(0, $barang->stok - $item['jumlah']);
    $barang->save();

    DetailPeminjaman::create([
        'id_peminjaman' => $peminjaman->id_peminjaman,
        'id_barang' => $barang->id_barang,
        'jumlah' => $item['jumlah'],
        'status_peminjaman' => 'Menunggu Sarpras',
        'status_pengembalian' => 'Belum',
        'tanggal_pengembalian' => null,
    ]);
}
DB::commit();
$peminjaman->load('details.barang', 'guru', 'siswa');


$sarpras = \App\Models\User::where('role', 'sarpras')->get();

// ✅ notif Laravel (boleh per barang)
foreach ($peminjaman->details as $detail) {
    foreach ($sarpras as $admin) {
        $admin->notify(new \App\Notifications\PengajuanMasuk($detail));
    }
}

// 🔥 FCM HANYA SEKALI
$tokens = \App\Models\User::where('role', 'sarpras')
    ->whereNotNull('fcm_token')
    ->pluck('fcm_token')
    ->unique()
    ->toArray();

if (count($tokens)) {

    $messaging = app('firebase.messaging');

foreach ($tokens as $token) {

    $message = \Kreait\Firebase\Messaging\CloudMessage::new()
        ->withData([
            'title' => 'Pengajuan Baru',
            'body'  => $peminjaman->guru->nama .
                       ' mengajukan ' .
                       $peminjaman->details->count() .
                       ' barang',
            'url'   => '/sarpras/peminjaman'
        ])
        ->toToken($token);

    $messaging->send($message);
}
}
if ($noWa) {
    WhatsappController::ajukanPeminjaman($peminjaman);
}


// Hapus session agar tidak kembali ke form lagi
session()->forget('barangDipilih');

return redirect()->route('guru.dashboard')
        ->with('success', 'Peminjaman berhasil dibuat. Menunggu persetujuan guru.');

        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error create peminjaman: '.$e->getMessage());
        return back()->with('error', 'Gagal menyimpan peminjaman: '.$e->getMessage());
    }
}

}
