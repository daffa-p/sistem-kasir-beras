<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');

            // id_pengguna = kasir yang membuat transaksi
            $table->foreignId('id_pengguna')
                ->constrained('pengguna', 'id_pengguna')
                ->restrictOnDelete();
            $table->foreignId('id_status')
                ->constrained('status_penjualan', 'id_status')
                ->restrictOnDelete();

            $table->dateTime('tanggal')->index();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};