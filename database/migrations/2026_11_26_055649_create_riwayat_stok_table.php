<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id('id_riwayat_stok');

            $table->foreignId('id_beras')
                ->constrained('beras', 'id_beras')
                ->restrictOnDelete();
            $table->foreignId('id_pengguna')
                ->constrained('pengguna', 'id_pengguna')
                ->restrictOnDelete();

            $table->string('jenis_perubahan', 20); // masuk, keluar, penyesuaian
            $table->decimal('jumlah_kg', 10, 2);
            $table->decimal('stok_sebelum', 10, 2);
            $table->decimal('stok_sesudah', 10, 2);
            $table->text('keterangan');
            $table->dateTime('tanggal')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
