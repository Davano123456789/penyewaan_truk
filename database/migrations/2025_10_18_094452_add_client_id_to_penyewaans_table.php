<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Tambah kolom client_id (tetap nama client_id, tapi relasi ke users)
            $table->unsignedBigInteger('client_id')->nullable()->after('id');
            
            // Foreign key ke tabel users
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};