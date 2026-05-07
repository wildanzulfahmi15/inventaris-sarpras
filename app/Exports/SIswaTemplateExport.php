<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SiswaTemplateExport implements FromArray, WithHeadings,ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nis',
            'nama',
            'kelas'
        ];
    }

    public function array(): array
    {
        return [
            ['123456', 'Rahma Farhah', 'XI DKV 2']
        ];
    }
}
