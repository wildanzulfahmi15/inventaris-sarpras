<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PeminjamanController;
use App\Models\Barang;

Route::get('/siswa', [PeminjamanController::class, 'getSiswa']);

Route::get('/guru-by-jenis', [PeminjamanController::class, 'getGuruByJenis']);
Route::get('/mapel-by-jenis', [PeminjamanController::class, 'getMapelByJenis']);

Route::get('/barang', function (Request $request) {
    return Barang::where('kategori', $request->kategori)
        ->where('nama_barang', 'like', '%' . $request->q . '%')
        ->get(['id_barang', 'nama_barang', 'stok']);
});
