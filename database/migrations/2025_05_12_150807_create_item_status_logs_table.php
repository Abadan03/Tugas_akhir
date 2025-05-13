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
        Schema::create('item_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('status_master_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedBigInteger('note_master_id')->nullable();
            $table->decimal('repair_cost', 15, 2)->nullable();
            $table->string('evidence')->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('status_master_id')->references('id')->on('status_masters')->onDelete('set null');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('set null');
            $table->foreign('note_master_id')->references('id')->on('note_masters')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_status_logs');
    }
};
