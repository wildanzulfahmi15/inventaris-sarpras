<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tempat extends Model
{
    use HasFactory;
protected $primaryKey = 'id';

    protected $table = 'tempat';

    protected $fillable = [
        'nama',
        'kategori',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /* ================= RELATION ================= */

    // satu tempat bisa dipakai di banyak peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
public function tempat()
{
    return $this->belongsTo(Tempat::class, 'ruangan', 'id');
}



    /* ================= SCOPE (OPSIONAL TAPI BERGUNA) ================= */

    // hanya tempat aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', 1);
    }

    // filter kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
