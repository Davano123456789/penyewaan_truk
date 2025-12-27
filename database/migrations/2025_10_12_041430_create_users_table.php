<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('kata_sandi');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->integer('umur')->nullable();
            $table->string('gambar')->nullable();
            $table->foreignId('peran_id')->constrained('perans')->onDelete('cascade');
            $table->timestamp('email_verified_at')->nullable(); // untuk verifikasi email
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
