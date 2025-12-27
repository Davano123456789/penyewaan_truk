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
        Schema::table('keranjangs', function (Blueprint $table) {
            // Tambah kolom client_id jika belum ada
            if (!Schema::hasColumn('keranjangs', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id');
                $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            }
            
            // Tambah kolom longitude_penjemputan jika belum ada
            if (!Schema::hasColumn('keranjangs', 'longitude_penjemputan')) {
                $table->decimal('longitude_penjemputan', 10, 7)->nullable()->after('latitude_penjemputan');
            }
            
            // Tambah kolom latitude_antar jika belum ada
            if (!Schema::hasColumn('keranjangs', 'latitude_antar')) {
                $table->decimal('latitude_antar', 10, 7)->nullable()->after('longitude_penjemputan');
            }
            
            // Tambah kolom longitude_antar jika belum ada
            if (!Schema::hasColumn('keranjangs', 'longitude_antar')) {
                $table->decimal('longitude_antar', 10, 7)->nullable()->after('latitude_antar');
            }
            
            // Tambah kolom parkir_latitude jika belum ada
            if (!Schema::hasColumn('keranjangs', 'parkir_latitude')) {
                $table->decimal('parkir_latitude', 10, 7)->nullable()->after('longitude_antar');
            }
            
            // Tambah kolom parkir_longitude jika belum ada
            if (!Schema::hasColumn('keranjangs', 'parkir_longitude')) {
                $table->decimal('parkir_longitude', 10, 7)->nullable()->after('parkir_latitude');
            }
            
            // Tambah kolom total_jarak jika belum ada
            if (!Schema::hasColumn('keranjangs', 'total_jarak')) {
                $table->decimal('total_jarak', 10, 2)->nullable()->after('harga_sewa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum hapus kolom
            if (Schema::hasColumn('keranjangs', 'client_id')) {
                $table->dropForeign(['client_id']);
            }
            
            // Hapus kolom
            $columns = [
                'client_id',
                'longitude_penjemputan',
                'latitude_antar',
                'longitude_antar',
                'parkir_latitude',
                'parkir_longitude',
                'total_jarak'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('keranjangs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};