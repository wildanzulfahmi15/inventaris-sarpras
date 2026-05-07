<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';
    public $timestamps = false;

protected $fillable = [
    'nama_mapel',
    'jenis_mapel',
    'id_jurusan'
];

public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'id_jurusan');
}

}
