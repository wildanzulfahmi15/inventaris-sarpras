<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapel', function (Blueprint $table) {
            $table->id('id_mapel');

            $table->string('nama_mapel', 100);

            $table->enum('jenis_mapel', [
                'jurusan',
                'umum',
                'ekskul'
            ])->default('umum');

            $table->unsignedBigInteger('id_jurusan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapel');
    }
};