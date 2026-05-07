<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;



class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang'; 

    public $timestamps = false;

protected $fillable = [
    'nama_barang', 'stok', 'id_kategori', 'gambar'
];

public function kategori()
{
    return $this->belongsTo(Kategori::class, 'id_kategori');
}
public function detailPeminjaman()
{
    return $this->hasMany(\App\Models\DetailPeminjaman::class, 'id_barang');
}
public function getStokDipinjamAttribute()
{
    return $this->detailPeminjaman()
        ->where('status_peminjaman', 'Disetujui')
        ->where('status_pengembalian', '!=', 'Selesai')
        ->sum('jumlah');
}
public function getStokTersediaAttribute()
{
    return max(0, $this->stok - $this->stok_dipinjam);
}



}
