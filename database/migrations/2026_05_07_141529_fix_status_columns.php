<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE peminjaman
            DROP CONSTRAINT IF EXISTS peminjaman_status_check
        ");

        DB::statement("
            ALTER TABLE peminjaman
            ADD CONSTRAINT peminjaman_status_check
            CHECK (status IN (
                'Dipinjam',
                'Dikembalikan',
                'Ditolak',
                'Diajukan'
            ))
        ");

        DB::statement("
            ALTER TABLE detail_peminjaman
            DROP CONSTRAINT IF EXISTS detail_peminjaman_status_peminjaman_check
        ");

        DB::statement("
            ALTER TABLE detail_peminjaman
            ADD CONSTRAINT detail_peminjaman_status_peminjaman_check
            CHECK (status_peminjaman IN (
                'Menunggu Sarpras',
                'Disetujui',
                'Ditolak Sarpras',
                'Stok Habis'
            ))
        ");

        DB::statement("
            ALTER TABLE detail_peminjaman
            DROP CONSTRAINT IF EXISTS detail_peminjaman_status_pengembalian_check
        ");

        DB::statement("
            ALTER TABLE detail_peminjaman
            ADD CONSTRAINT detail_peminjaman_status_pengembalian_check
            CHECK (status_pengembalian IN (
                'Belum',
                'Menunggu Sarpras',
                'Selesai',
                'Ditolak Sarpras'
            ))
        ");
    }

    public function down(): void
    {
    }
};