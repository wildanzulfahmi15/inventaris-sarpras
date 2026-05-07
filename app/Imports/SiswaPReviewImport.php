<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SiswaPreviewImport implements ToCollection
{
    public array $rows = [];

    public function collection(Collection $collection)
    {
        $nisInFile = [];

        foreach ($collection as $i => $row) {
            if ($i === 0) continue; // skip header

            $nis   = trim($row[0] ?? '');
            $nama  = trim($row[1] ?? '');
            $kelas = trim($row[2] ?? '');

            $status = 'ok';
            $pesan  = 'Siap diimpor';

            // validasi kosong
            if (!$nis || !$nama || !$kelas) {
                $status = 'error';
                $pesan = 'Kolom wajib tidak boleh kosong';
            }

            // cek duplikat dalam file
            if (in_array($nis, $nisInFile)) {
                $status = 'error';
                $pesan = 'NIS duplikat di file';
            }

            // cek kelas
            $kelasModel = Kelas::where('nama_kelas', $kelas)->first();
            if (!$kelasModel) {
                $status = 'error';
                $pesan = 'Kelas tidak ditemukan';
            }

            // cek NIS sudah ada
            if (Siswa::where('nis', $nis)->exists()) {
                $status = 'error';
                $pesan = 'NIS sudah terdaftar';
            }

            $nisInFile[] = $nis;

            $this->rows[] = [
                'nis' => $nis,
                'nama' => $nama,
                'kelas' => $kelas,
                'kelas_id' => $kelasModel?->id_kelas,
                'status' => $status,
                'pesan' => $pesan
            ];
        }
    }
}
    