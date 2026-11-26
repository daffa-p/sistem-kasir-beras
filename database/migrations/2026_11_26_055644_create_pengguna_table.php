<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('id_pengguna');

            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->string('password');

            $table->string('role', 20);
            $table->string('status_akun', 20)->default('aktif');

            $table->string('no_telp', 20)->nullable();
            $table->string('foto_profil')->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};