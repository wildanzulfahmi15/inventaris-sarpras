<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('pengembalian', function (Blueprint $table) {
        $table->increments('id_pengembalian');

        // tipe harus sama dengan tabel peminjaman!
        $table->unsignedInteger('id_peminjaman');
        $table->foreign('id_peminjaman')
              ->references('id_peminjaman')
              ->on('peminjaman')
              ->onDelete('cascade');

        $table->date('tanggal_kembali')->nullable();
        $table->enum('status', ['Menunggu Guru','Menunggu Sarpras','Selesai'])
              ->default('Menunggu Guru');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
