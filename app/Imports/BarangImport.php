<?php

namespace App\Imports;


use App\Models\Barang;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['nama_barang']) ||
            empty($row['stok']) ||
            empty($row['kategori'])
        ) {
            return null;
        }

        if (!is_numeric($row['stok'])) {
            return null;
        }

        $kategori = Kategori::where('nama_kategori', $row['kategori'])->first();

        if (!$kategori) return null;

        return new Barang([
            'nama_barang' => $row['nama_barang'],
            'stok' => $row['stok'],
            'id_kategori' => $kategori->id_kategori
        ]);
    }
}

