<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nis'], $row['nama'], $row['kelas'])) {
            return null;
        }

        $kelas = Kelas::where('nama_kelas', $row['kelas'])->first();

        if (!$kelas) return null;

        return new Siswa([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'id_kelas' => $kelas->id_kelas,
        ]);
    }
}
