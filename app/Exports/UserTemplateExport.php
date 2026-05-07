<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class UserTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['nama', 'role', 'jurusan', 'password'],
            ['Budi', 'guru', 'RPL', '123456'],
            ['Asep', 'guru', '', '123456'],
            ['Admin', 'sarpras', '', 'admin123'],
        ];
    }
}
