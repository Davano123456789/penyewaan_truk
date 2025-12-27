<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('armadas', function (Blueprint $table) {
            $table->id();
            $table->string('no_polisi')->unique();
            $table->unsignedBigInteger('sopir_id')->nullable();
            $table->unsignedBigInteger('parkir_id')->nullable();
            $table->string('merek')->nullable();
            $table->string('jenis')->nullable();
            $table->integer('kapasitas')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', ['tersedia', 'tidak_tersedia', 'perawatan'])->default('tersedia');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('sopir_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('parkir_id')->references('id')->on('parkirs')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('armadas');
    }
};
