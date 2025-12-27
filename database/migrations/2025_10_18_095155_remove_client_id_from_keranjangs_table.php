<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            // 1. HAPUS FOREIGN KEY DULU
            $table->dropForeign('keranjangs_client_id_foreign');
            
            // 2. BARU HAPUS KOLOM
            $table->dropColumn('client_id');
        });
    }

    public function down()
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            // Rollback: tambahkan kembali
            $table->unsignedBigInteger('client_id')->nullable()->after('id');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};