<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beras', function (Blueprint $table) {
            $table->id('id_beras');

            $table->foreignId('id_supplier')
                ->constrained('supplier', 'id_supplier')
                ->restrictOnDelete();
            $table->foreignId('id_kategori')
                ->constrained('kategori_beras', 'id_kategori')
                ->restrictOnDelete();

            $table->string('nama_beras', 150)->index();
            $table->decimal('harga_per_kg', 12, 2);
            $table->decimal('stok_kg', 10, 2)->default(0);
            $table->string('foto', 255)->nullable(); 
            $table->string('status_beras', 20)->default('aktif'); // aktif, nonaktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beras');
    }
};
