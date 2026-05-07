<?php

namespace App\Imports;

use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserPreviewImport implements ToCollection
{
    public array $rows = [];

    public function collection(Collection $collection)
    {
        foreach ($collection as $i => $row) {

            if ($i === 0) continue; // skip header

            $nama     = trim($row[0] ?? '');
            $role     = strtolower(trim($row[1] ?? ''));
            $jurusan  = trim($row[2] ?? '');
            $password = trim($row[3] ?? '');

            if ($password === '') {
                $password = '123456';
            }


            $status = 'ok';
            $pesan  = 'Siap diimport';

            if (strlen($password) < 6) {
                $status = 'error';
                $pesan = 'Password minimal 6 karakter';
            }

            if (!$nama || !$role) {
                $status = 'error';
                $pesan = 'Nama atau role kosong';
            }

            if (!in_array($role, ['guru', 'sarpras'])) {
                $status = 'error';
                $pesan = 'Role harus guru / sarpras';
            }

            $jurusanModel = null;

            if ($role === 'guru' && $jurusan !== '') {
                $jurusanModel = Jurusan::where('nama_jurusan', $jurusan)->first();
                if (!$jurusanModel) {
                    $status = 'error';
                    $pesan = 'Jurusan tidak ditemukan';
                }
            }

            $this->rows[] = [
                'nama' => $nama,
                'role' => $role,
                'jurusan' => $jurusan ?: '-',
                'password' => $password,
                'status' => $status,
                'pesan' => $pesan,
            ];
        }
    }
}
