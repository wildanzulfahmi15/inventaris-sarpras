<?php

namespace App\Imports;

use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class JurusanPreviewImport implements ToCollection
{
    public array $rows = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            if ($i === 0) continue;

            $nama = trim($row[0] ?? '');

            $status = 'ok';
            $pesan  = 'Siap diimport';

            if (!$nama) {
                $status = 'error';
                $pesan = 'Nama jurusan kosong';
            }

            if (Jurusan::where('nama_jurusan', $nama)->exists()) {
                $status = 'error';
                $pesan = 'Jurusan sudah ada';
            }

            $this->rows[] = [
                'nama_jurusan' => $nama,
                'status' => $status,
                'pesan' => $pesan
            ];
        }
    }
}
