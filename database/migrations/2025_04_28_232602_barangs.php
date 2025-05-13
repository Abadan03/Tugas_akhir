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
        //
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->integer('harga_awal');
            $table->string('kategori');
            $table->string('nama_siswa')->nullable();
            $table->string('tipe');
            $table->string('status');
            // $table->foreignId('barang_rusak_id')->constrained('barang_rusaks')->onDelete('cascade');
            // $table->string('barangrusak_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('kodeQR');
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('barangs');
    }
};
