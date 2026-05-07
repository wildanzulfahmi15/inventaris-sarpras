<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class KelasTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['nama_kelas', 'jurusan'],
            ['XI RPL 1', 'RPL'],
            ['XI TKJ 1', 'TKJ'],
        ];
    }
}
