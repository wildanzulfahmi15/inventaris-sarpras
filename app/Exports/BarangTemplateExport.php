<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BarangTemplateExport implements FromArray, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['nama_barang', 'stok', 'kategori'],
            ['Proyektor', 5, 'Elektronik'],
            ['Gitar Akustik', 3, 'Kesenian'],
        ];
    }
}
