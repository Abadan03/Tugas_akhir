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
        // $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            // $table->string('bukti')->nullable();
            // $table->string('keterangan');
            $table->unsignedBigInteger('barang_rusaks_id');

            $table->integer('biaya_perbaikan')->nullable();
            $table->timestamps();

            $table->foreign('barang_rusaks_id')->references('id')->on('barang_rusaks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('pembayaran');
    }
};
