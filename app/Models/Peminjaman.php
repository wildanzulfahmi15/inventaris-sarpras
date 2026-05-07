<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $incrementing = true;
    protected $keyType = 'int';

    // SESUAIKAN: jika ada created_at updated_at -> true
    public $timestamps = true;

    protected $fillable = [
        'id_siswa',
        'id_guru',
        'id_mapel',
        'tanggal_pinjam',
        'ruangan',
        'no_wa',
        'foto_pinjam',
        'alasan',
        'status'
    ];

    /* ---------- RELASI ---------- */

    // header -> banyak detail
    public function detail()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
    public function details()
{
    return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
}

// app/Models/Peminjaman.php — tambahkan ini
public function detailPeminjaman()
{
    return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
}
    // siswa peminjam
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // guru pengampu mapel
    public function guru()
    {
        return $this->belongsTo(User::class, 'id_guru', 'id_user');
    }

    // mapel terkait
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    // OPTIONAL: relasi barang lewat detail_peminjaman
    public function barang()
    {
        return $this->belongsToMany(
            Barang::class,
            'detail_peminjaman',
            'id_peminjaman',
            'id_barang'
        )->withPivot(['jumlah', 'status_peminjaman', 'status_pengembalian']);
    }
        public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'ruangan', 'id');
    }
}
