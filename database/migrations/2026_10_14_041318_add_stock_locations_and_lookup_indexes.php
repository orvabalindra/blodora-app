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
        Schema::table('stok__darahs', function (Blueprint $table): void {
            $table->string('lokasi')->nullable()->after('pendonor_id')->index();
            $table->index(['tanggal_kadaluarsa', 'golongan_darah'], 'stok_expiry_blood_index');
        });

        Schema::table('pendonors', function (Blueprint $table): void {
            $table->index('nama_lengkap');
            $table->index('no_hp');
        });

        Schema::table('rumah_sakits', function (Blueprint $table): void {
            $table->index('nama_rs');
            $table->index('no_telp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rumah_sakits', function (Blueprint $table): void {
            $table->dropIndex(['nama_rs']);
            $table->dropIndex(['no_telp']);
        });

        Schema::table('pendonors', function (Blueprint $table): void {
            $table->dropIndex(['nama_lengkap']);
            $table->dropIndex(['no_hp']);
        });

        Schema::table('stok__darahs', function (Blueprint $table): void {
            $table->dropIndex('stok_expiry_blood_index');
            $table->dropIndex(['lokasi']);
            $table->dropColumn('lokasi');
        });
    }
};
