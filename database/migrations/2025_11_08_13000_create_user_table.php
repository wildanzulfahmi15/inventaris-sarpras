<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id_user'); // PRIMARY KEY sesuai model
            $table->string('nama', 100);
            $table->string('password');
            $table->enum('role', ['guru', 'sarpras']); // role sesuai yang kamu bilang
            // no timestamps karena model: public $timestamps = false;
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
