<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_kelas']) || empty($row['jurusan'])) return null;

        $jurusan = Jurusan::where('nama_jurusan', $row['jurusan'])->first();
        if (!$jurusan) return null;

        return new Kelas([
            'nama_kelas' => $row['nama_kelas'],
            'id_jurusan' => $jurusan->id_jurusan
        ]);
    }
}
