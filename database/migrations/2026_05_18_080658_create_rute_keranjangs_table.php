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
        Schema::create('rute_keranjangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keranjang_id');
            $table->string('tempat_jemput')->nullable();
            $table->string('tempat_antar')->nullable();
            $table->decimal('latitude_penjemputan', 10, 7)->nullable();
            $table->decimal('longitude_penjemputan', 10, 7)->nullable();
            $table->decimal('latitude_antar', 10, 7)->nullable();
            $table->decimal('longitude_antar', 10, 7)->nullable();
            $table->decimal('parkir_latitude', 10, 7)->nullable();
            $table->decimal('parkir_longitude', 10, 7)->nullable();
            $table->decimal('total_jarak', 10, 2)->nullable();
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
        Schema::dropIfExists('rute_keranjangs');
    }
};
