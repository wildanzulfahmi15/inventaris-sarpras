<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->increments('id_peminjaman'); // PK

            // Foreign key ke siswa (optional)
$table->unsignedInteger('id_siswa')->nullable();
$table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('set null');

            // Foreign key ke user (sebagai guru)
            $table->integer('id_guru')->unsigned()->nullable();
            $table->foreign('id_guru')->references('id_user')->on('user')->nullOnDelete();

            // Foreign key ke barang
$table->unsignedInteger('id_barang')->nullable();

            $table->unsignedBigInteger('id_user')->nullable();

            $table->date('tanggal_pinjam')->nullable();
            $table->integer('jumlah')->nullable();
            $table->text('alasan')->nullable();

            $table->enum('status', [
                'Menunggu Guru',
                'Menunggu Sarpras',
                'Dipinjam',
                'Selesai'
            ])->default('Menunggu Guru');

            // Tambahan sesuai model
            $table->string('ruangan', 30)->nullable();
            $table->string('no_wa', 20)->nullable();

            // NO timestamps karena $timestamps = false
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
