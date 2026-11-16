<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruangan_id')->constrained('ruangans');
            $table->foreignId('kategori_barang_id')->constrained('kategori_barangs');
            $table->string('kode_inventaris')->unique();
            $table->string('nama_barang');
            $table->string('merk');
            $table->integer('tahun_pengadaan');
            $table->enum('kondisi', ['baik', 'rusak ringan', 'rusak berat']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
