<?php

namespace App\Imports;

use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MapelPreviewImport implements ToCollection
{
    public array $rows = [];

    public function collection(Collection $collection)
    {
        foreach ($collection as $i => $row) {
            if ($i === 0) continue;

            $nama = trim($row[0] ?? '');
            $jenis = strtolower(trim($row[1] ?? ''));
            $jurusan = trim($row[2] ?? '');

            $status = 'ok';
            $pesan = 'Siap diimport';
            $idJurusan = null;

            if (!$nama || !$jenis) {
                $status = 'error';
                $pesan = 'Nama / jenis kosong';
            }

            if (!in_array($jenis, ['umum','jurusan','ekskul'])) {
                $status = 'error';
                $pesan = 'Jenis harus: umum / jurusan / ekskul';
            }

            if ($jenis === 'jurusan') {
                $jur = Jurusan::where('nama_jurusan', $jurusan)->first();
                if (!$jur) {
                    $status = 'error';
                    $pesan = 'Jurusan tidak ditemukan';
                } else {
                    $idJurusan = $jur->id_jurusan;
                }
            }

            $this->rows[] = [
                'nama_mapel' => $nama,
                'jenis' => $jenis,
                'jurusan' => $jurusan,
                'status' => $status,
                'pesan' => $pesan,
                'id_jurusan' => $idJurusan
            ];
        }
    }
}
