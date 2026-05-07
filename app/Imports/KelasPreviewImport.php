<?php

namespace App\Imports;

use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class KelasPreviewImport implements ToCollection
{
    public array $rows = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            if ($i === 0) continue;

            $nama = trim($row[0] ?? '');
            $jurusan = trim($row[1] ?? '');

            $status = 'ok';
            $pesan = 'Siap diimport';

            if (!$nama || !$jurusan) {
                $status = 'error';
                $pesan = 'Kolom tidak boleh kosong';
            }

            $jurusanModel = Jurusan::where('nama_jurusan', $jurusan)->first();
            if (!$jurusanModel) {
                $status = 'error';
                $pesan = 'Jurusan tidak ditemukan';
            }

            $this->rows[] = [
                'nama_kelas' => $nama,
                'jurusan' => $jurusan,
                'status' => $status,
                'pesan' => $pesan
            ];
        }
    }
}
