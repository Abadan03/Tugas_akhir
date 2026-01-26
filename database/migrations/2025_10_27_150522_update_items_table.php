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
        Schema::table('barangs', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->constrained('category_masters')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('status_masters')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('tipe_id')->nullable()->constrained('type_masters')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('items_id')->nullable()->constrained('items_masters')->cascadeOnUpdate()->nullOnDelete();

            // hapus kolom lama jika sudah tidak dipakai
            $table->dropColumn(['kategori', 'status', 'tipe','item']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
