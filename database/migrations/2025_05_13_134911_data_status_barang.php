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
         Schema::create('data_status_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            // $table->foreignId('status_id')->constrained('m_statuses')->onDelete('restrict');
            $table->foreignId('pinjaman_id')->nullable()->constrained('pinjamans')->onDelete('set null');
            // $table->foreignId('keterangan_id')->nullable()->constrained('m_keterangans')->onDelete('set null');
            $table->decimal('biaya_perbaikan', 15, 2)->nullable();
            $table->foreignId('pembayaran_id')->nullable()->constrained('pembayaran')->onDelete('set null');
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

        Schema::dropIfExists('pembayaran');
    }
};
