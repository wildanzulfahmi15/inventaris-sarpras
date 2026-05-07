<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama']) || empty($row['role'])) return null;

        $idJurusan = null;

        if ($row['role'] === 'guru' && !empty($row['jurusan'])) {
            $jur = Jurusan::where('nama_jurusan', $row['jurusan'])->first();
            if ($jur) {
                $idJurusan = $jur->id_jurusan;
            }
        }

        return new User([
            'nama' => $row['nama'],
            'role' => $row['role'],
            'id_jurusan' => $idJurusan,
            'password' => Hash::make($row['password'] ?? '123456'),
        ]);
    }
}
