<?php

namespace App\Imports;

use App\Models\Mapel;
use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MapelImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['nama_mapel']) ||
            empty($row['jenis_mapel'])
        ) {
            return null;
        }

        $jenis = strtolower($row['jenis_mapel']);

        if (!in_array($jenis, ['umum', 'jurusan', 'ekskul'])) {
            return null;
        }

        $idJurusan = null;

        if ($jenis === 'jurusan') {
            $jurusan = Jurusan::where('nama_jurusan', $row['jurusan'] ?? '')->first();
            if (!$jurusan) return null;

            $idJurusan = $jurusan->id_jurusan;
        }

        return new Mapel([
            'nama_mapel' => $row['nama_mapel'],
            'jenis_mapel' => $jenis,
            'id_jurusan' => $idJurusan
        ]);
    }
}
