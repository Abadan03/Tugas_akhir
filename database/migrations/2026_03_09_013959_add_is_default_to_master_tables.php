<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_masters', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('defaultTrigger');
        });

        Schema::table('status_masters', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('warna');
        });

        Schema::table('type_masters', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('nama_tipe');
        });
    }

    public function down(): void
    {
        Schema::table('category_masters', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
        Schema::table('status_masters', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
        Schema::table('type_masters', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
