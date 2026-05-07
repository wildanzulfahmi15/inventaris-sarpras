<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id('id_detail');

            $table->unsignedBigInteger('id_peminjaman')->nullable();
            $table->unsignedBigInteger('id_barang')->nullable();

            $table->integer('jumlah')->default(1);

            $table->string('status_peminjaman')
                ->default('Menunggu Sarpras');

            $table->string('status_pengembalian')
                ->default('Belum');

            $table->date('tanggal_pengembalian')->nullable();

            $table->string('foto_pengembalian')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};