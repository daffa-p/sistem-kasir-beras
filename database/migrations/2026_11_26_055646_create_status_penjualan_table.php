<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_penjualan', function (Blueprint $table) {
            $table->id('id_status');
            $table->string('nama_status', 30)->unique(); // Berhasil, Batal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_penjualan');
    }
};
