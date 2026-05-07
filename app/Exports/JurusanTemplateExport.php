<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class JurusanTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['nama_jurusan'],
            ['RPL'],
            ['TKJ'],
        ];
    }
}
