<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MapelTemplateExport implements FromArray, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['nama_mapel', 'jenis_mapel', 'jurusan'],
            ['Matematika', 'umum', ''],
            ['Pemrograman Web', 'jurusan', 'RPL'],
            ['Futsal', 'ekskul', ''],
        ];
    }
}
