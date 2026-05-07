<?php

namespace App\Imports;

use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JurusanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_jurusan'])) return null;

        if (Jurusan::where('nama_jurusan', $row['nama_jurusan'])->exists()) {
            return null;
        }

        return new Jurusan([
            'nama_jurusan' => $row['nama_jurusan']
        ]);
    }
}
