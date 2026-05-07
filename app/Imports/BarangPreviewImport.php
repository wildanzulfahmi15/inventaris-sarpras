<?php

namespace App\Imports;

use App\Models\Kategori;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BarangPreviewImport implements ToCollection
{
    public array $rows = [];

    public function collection(Collection $collection)
    {
        $namaInFile = [];

        foreach ($collection as $i => $row) {
            if ($i === 0) continue; // skip header

            $nama     = trim($row[0] ?? '');
            $stok     = trim($row[1] ?? '');
            $kategori = trim($row[2] ?? '');

            $status = 'ok';
            $pesan  = 'Siap diimport';

            if (!$nama || $stok === '' || !$kategori) {
                $status = 'error';
                $pesan = 'Kolom tidak boleh kosong';
            }

            if (!is_numeric($stok)) {
                $status = 'error';
                $pesan = 'Stok harus angka';
            }

            if (in_array(strtolower($nama), $namaInFile)) {
                $status = 'error';
                $pesan = 'Nama barang duplikat di file';
            }

            $kategoriModel = Kategori::where('nama_kategori', $kategori)->first();
            if (!$kategoriModel) {
                $status = 'error';
                $pesan = 'Kategori tidak ditemukan';
            }

            $namaInFile[] = strtolower($nama);

            $this->rows[] = [
                'nama_barang' => $nama,
                'stok' => $stok,
                'kategori' => $kategori,
                'status' => $status,
                'pesan' => $pesan,
                'id_kategori' => $kategoriModel?->id_kategori
            ];
        }
    }
}
