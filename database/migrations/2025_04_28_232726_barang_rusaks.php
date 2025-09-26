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
        Schema::create('barang_rusaks', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            // $table->foreignId('pinjaman_id')->constrained('pinjamans')->onDelete('cascade');
            // $table->foreignId('pembayaraan_id')->constrained('pembayaran')->onDelete('cascade');
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('pinjaman_id')->nullable();;
            // $table->string('keterangan')->nullable();
            // $table->unsignedBigInteger('pembayaran_id');
            // $table->decimal('biaya_perbaikan', 15, 2)->nullable();
            // $table->text('keterangan')->nullable();
            $table->string('surat')->nullable();
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->foreign('pinjaman_id')->references('id')->on('pinjamans')->onDelete('cascade');
            // $table->foreign('pembayaran_id')->references('id')->on('pembayaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('barang_rusaks');
    }
};
