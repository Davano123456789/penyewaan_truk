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
        Schema::create('pembatalan_sewas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keranjang_id');
            $table->text('alasan_batal')->nullable();
            $table->text('catatan')->nullable(); // catatan admin
            $table->string('bukti_refund')->nullable();
            $table->decimal('nominal_refund', 15, 2)->nullable();
            $table->timestamps();

            // Foreign key to keranjangs table
            $table->foreign('keranjang_id')->references('id')->on('keranjangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembatalan_sewas');
    }
};
