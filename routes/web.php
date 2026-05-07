<?php
use App\Http\Controllers\AuthController;

use App\Http\Middleware\SarprasMiddleware;
use App\Http\Controllers\SarprasController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\JurusanKelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TempatController;
use App\Http\Controllers\NotificationController;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

Route::get('/mapel-by-jenis', [PeminjamanController::class, 'getMapelByJenis']);
Route::get('/tempat-by-kategori', [TempatController::class, 'byKategori']);
Route::get('/pengembalian', [PengembalianController::class, 'form'])->name('pengembalian'); // GET form
// HANDLE JIKA DIAKSES VIA GET (browser address bar)
Route::get('/pengembalian/cari', function () {
    return redirect()
        ->route('pengembalian')
        ->with('error', 'Silakan masukkan NIS terlebih dahulu.');
});

Route::post('/save-token', [UserController::class, 'saveToken'])->middleware('auth');
Route::post('/pengembalian/cari', [PengembalianController::class, 'cari'])->name('pengembalian.cari'); // POST cari
// HARUS DITARUH DI LUAR MIDDLEWARE AUTH
Route::post('/pengembalian/kembalikan/{id}', 
    [PengembalianController::class, 'kembalikan']
)->name('pengembalian.kembalikan');
Route::post('/save-fcm-token', function (\Illuminate\Http\Request $request) {

    auth()->user()->update([
        'fcm_token' => $request->token
    ]);

    return response()->json([
        'success' => true
    ]);
})->middleware('auth');

Route::get('/test-notif', function () {

    $user = \App\Models\User::whereNotNull('fcm_token')->first();

    $messaging = app('firebase.messaging');

$message = CloudMessage::new()
    ->withNotification(Notification::create(
        'TEST NOTIF 🔥',
        'Klik untuk lihat detail'
    ))
    ->withData([
        'url' => '/sarpras/peminjaman' // 🔥 tujuan
    ])
    ->toToken($user->fcm_token);

    $messaging->send($message);

    return 'Notif terkirim';
});

Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) abort(404);
    return response()->file($fullPath);
})->where('path', '.*');
Route::get('/', function () { return view('home'); })->name('home');

    
Route::middleware(['auth', 'guru'])->group(function () {

    Route::get('/peminjaman/barang', 
        [PeminjamanController::class, 'pilihBarang']
    )->name('peminjaman.pilihBarang');

    Route::get('/peminjaman/barang/{idKategori}', 
        [PeminjamanController::class, 'pilihBarang']
    );
});

Route::middleware(['auth'])->group(function() {
    
    Route::post('guru/peminjaman/form', [PeminjamanController::class, 'formBarang'])
            ->name('peminjaman.formBarang');
    Route::get('guru/peminjaman/form', [PeminjamanController::class, 'form'])
        ->name('peminjaman.form');
    
    Route::get('/api/siswa', [PeminjamanController::class, 'getSiswa']);
    Route::get('/api/mapel', [PeminjamanController::class, 'getmapel']);
    Route::get('/api/barang', [PeminjamanController::class, 'getBarang']);

    Route::post('guru/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('guru/peminjaman', [GuruController::class, 'guruIndex'])->name('guru.peminjaman');
    // per-item actions
Route::post('guru/peminjaman/detail/{id}', [GuruController::class, 'guruSetuju'])
    ->name('guru.detail.setuju');

Route::post('guru/peminjaman/detail/{id}/tolak', [GuruController::class, 'guruTolak'])
    ->name('guru.detail.tolak');
    Route::get('/guru/riwayat', [PeminjamanController::class, 'riwayatGuru'])
    ->name('guru.riwayat');
Route::get('/guru/riwayat/json', [PeminjamanController::class, 'riwayatGuruJson'])
    ->name('guru.riwayat.json');   
Route::post('/guru/kembalikan/{id}', [GuruController::class,'kembalikan'])
    ->name('guru.kembalikan');

        // Halaman konfirmasi pengembalian
    Route::get('/guru/pengembalian', [GuruController::class, 'pengembalian'])->name('guru.pengembalian');
Route::post('/guru/detail/{id}/setuju', 
    [GuruController::class, 'setujuPengembalian']
)->name('guru.pengembalian.setuju');

Route::post('/guru/detail/{id}/tolak', 
    [GuruController::class, 'tolakPengembalian']
)->name('guru.pengembalian.tolak');

});
Route::middleware(['auth'])->group(function() {
    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
    Route::get('/notif/list', function () {
    return auth()->user()->unreadNotifications->map(function ($n) {
        return [
            'id' => $n->id,
            'title' => $n->data['title'],
            'message' => $n->data['message'],
            'url' => $n->data['url'],
        ];
    });
});
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead']);
Route::post('/notifications/{id}/read',     [NotificationController::class, 'markRead']);

});


/*
|--------------------------------------------------------------------------
| ======================= SARPRAS =======================
|--------------------------------------------------------------------------
*/
Route::middleware(SarprasMiddleware::class)
    ->prefix('sarpras')
    ->name('sarpras.')
    ->group(function () {

    /* DASHBOARD */
    Route::get('/dashboard', [SarprasController::class, 'index'])->name('dashboard');

    /* WHATSAPP */
    Route::get('/whatsapp', fn () => view('sarpras.whatsapp'))->name('whatsapp');
    Route::get('/status', fn () => Http::get('http://localhost:3001/status')->json());
    Route::get('/qr', fn () => Http::get('http://localhost:3001/qr')->json());
    Route::post('/logout', fn () => Http::post('http://localhost:3001/logout')->json());
    /* PEMINJAMAN */
    Route::get('/peminjaman', [SarprasController::class, 'peminjaman'])->name('peminjaman');
    Route::post('/peminjaman/konfirmasi/{id}', [SarprasController::class, 'konfirmasi'])->name('detail.setuju');
    Route::post('/peminjaman/tolak/{id}', 
    [SarprasController::class, 'tolak']
)->name('detail.tolak');

    /* PENGEMBALIAN */
    Route::get('/pengembalian', [SarprasController::class, 'pengembalian'])->name('pengembalian');
    Route::post('/pengembalian/{id}/setuju', [SarprasController::class, 'setuju'])->name('pengembalian.setuju');
    Route::post('/pengembalian/{id}/tolak', [SarprasController::class, 'tolakPengembalian'])->name('pengembalian.tolak');

    /* ================= BARANG ================= */
    Route::prefix('barang')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');

        Route::post('/store', [BarangController::class, 'store'])->name('barang.store');
        Route::put('/{id}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

        Route::post('/import', [BarangController::class, 'import'])->name('barang.import');
        Route::get('/template', [BarangController::class, 'template'])->name('barang.template');
        Route::post('/preview', [BarangController::class, 'preview'])
    ->name('barang.preview');

    });


    //AKUN

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/', [UserController::class, 'store'])->name('user.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])
        ->name('user.reset');
        Route::post('/import', [UserController::class, 'import'])->name('user.import');
        Route::get('/template', [UserController::class, 'template'])->name('user.template');
        Route::post('/preview', [UserController::class, 'preview'])
        ->name('user.preview');

    });


    //MAPEL

    Route::prefix('mapel')->group(function () {
        Route::get('/', [MapelController::class, 'index'])->name('mapel.index');
        Route::post('/', [MapelController::class, 'store'])->name('mapel.store');
        Route::put('/{id}', [MapelController::class, 'update'])->name('mapel.update');
        Route::delete('/{id}', [MapelController::class, 'destroy'])->name('mapel.destroy');
        Route::post('/import', [MapelController::class, 'import'])->name('mapel.import');
        Route::post('/preview', [MapelController::class, 'preview'])->name('mapel.preview');
        Route::get('/template', [MapelController::class, 'template'])->name('mapel.template');

    });
        /* ================= TEMPAT / RUANGAN ================= */
    Route::prefix('tempat')->group(function () {
        Route::get('/', [TempatController::class, 'index'])->name('tempat.index');
        Route::post('/', [TempatController::class, 'store'])->name('tempat.store');
        Route::put('/{id}', [TempatController::class, 'update'])->name('tempat.update');
        Route::delete('/{id}', [TempatController::class, 'destroy'])->name('tempat.destroy');
    });


    /* ================= KATEGORI ================= */
    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/store', [KategoriController::class, 'store'])->name('kategori.store');
        Route::post('/ajax', [KategoriController::class, 'storeAjax'])->name('kategori.ajax');
        Route::post('/update/{id}', [KategoriController::class, 'updateAjax'])->name('kategori.update');
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    });

    /* ================= SISWA ================= */
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/siswa/search', [SiswaController::class, 'search'])->name('siswa.search');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::post('/siswa/preview', [SiswaController::class, 'preview'])->name('siswa.preview');
    Route::get('/siswa/template', [SiswaController::class, 'template'])->name('siswa.template');

    /* ================= JURUSAN & KELAS ================= */
    Route::get('/jurusan-kelas', [JurusanKelasController::class, 'index'])->name('jurusan.kelas');
    Route::post('/jurusan', [JurusanKelasController::class, 'storeJurusan'])->name('jurusan.store');
    Route::put('/jurusan/{id}', [JurusanKelasController::class, 'updateJurusan'])->name('jurusan.update');
    Route::delete('/jurusan/{id}', [JurusanKelasController::class, 'destroyJurusan'])->name('jurusan.delete');

    Route::post('/kelas', [JurusanKelasController::class, 'storeKelas'])->name('kelas.store');
    Route::put('/kelas/{id}', [JurusanKelasController::class, 'updateKelas'])->name('kelas.update');
    Route::delete('/kelas/{id}', [JurusanKelasController::class, 'destroyKelas'])->name('kelas.delete');
    Route::post('/jurusan/preview', [JurusanKelasController::class, 'previewJurusan'])->name('jurusan.preview');
    Route::post('/jurusan/import', [JurusanKelasController::class, 'importJurusan'])->name('jurusan.import');
    Route::get('/jurusan/template', [JurusanKelasController::class, 'templateJurusan'])->name('jurusan.template');

    Route::post('/kelas/preview', [JurusanKelasController::class, 'previewKelas'])->name('kelas.preview');
    Route::post('/kelas/import', [JurusanKelasController::class, 'importKelas'])->name('kelas.import');
    Route::get('/kelas/template', [JurusanKelasController::class, 'templateKelas'])->name('kelas.template');


    /* ================= RIWAYAT ================= */
    Route::get('/riwayat', [SarprasController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/pdf', [SarprasController::class, 'riwayatPdf'])->name('riwayat.pdf');
    Route::post('/riwayat/setujui/{id}', [SarprasController::class, 'setujuiDetail'])->name('riwayat.setujui');
    Route::delete('/riwayat/detail/{id}', [SarprasController::class, 'hapusDetailRiwayat'])->name('riwayat.hapus.detail');
    Route::get('/riwayat/json', [SarprasController::class, 'riwayatJson'])
    ->name('riwayat.json');

    
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
