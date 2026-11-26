<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');

            // unique: satu transaksi memiliki satu pembayaran utama
            $table->foreignId('id_penjualan')
                ->unique()
                ->constrained('penjualan', 'id_penjualan')
                ->restrictOnDelete();
            $table->foreignId('id_metode')
                ->constrained('metode_pembayaran', 'id_metode')
                ->restrictOnDelete();

            $table->decimal('jumlah_bayar', 12, 2);
            $table->decimal('uang_kembali', 12, 2)->default(0);
            $table->string('status_pembayaran', 20)->default('Lunas');
            $table->dateTime('tanggal_bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
