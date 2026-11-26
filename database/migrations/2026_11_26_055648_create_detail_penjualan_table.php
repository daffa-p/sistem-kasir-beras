<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id('id_detail');

            $table->foreignId('id_penjualan')
                ->constrained('penjualan', 'id_penjualan')
                ->restrictOnDelete();
            $table->foreignId('id_beras')
                ->constrained('beras', 'id_beras')
                ->restrictOnDelete();

            $table->decimal('jumlah_kg', 10, 2);
            $table->decimal('harga_per_kg', 12, 2); // snapshot harga saat transaksi
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
