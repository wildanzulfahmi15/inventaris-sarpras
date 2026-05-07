<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->increments('id_siswa'); // Primary key sesuai model
            $table->string('nis', 50)->nullable(); // kalau ada NIS
            $table->string('nama', 100);
            $table->string('kelas', 20)->nullable();
            $table->string('no_siswa', 20)->nullable(); // sesuai model
            // no timestamps karena $timestamps = false
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
