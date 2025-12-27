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
        Schema::create('penyewaans', function (Blueprint $table) {
            $table->id(); // kolom id (primary key)
            $table->decimal('harga_total', 15, 2)->nullable(); // kolom harga_total
            $table->string('status')->default('pending'); // kolom status
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaans');
    }
};
