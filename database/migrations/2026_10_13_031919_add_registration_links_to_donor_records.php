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
        Schema::table('riwayat__donors', function (Blueprint $table) {
            $table->foreignId('pendaftaran_id')
                ->nullable()
                ->unique()
                ->constrained('pendaftarans')
                ->nullOnDelete();
        });

        Schema::table('stok__darahs', function (Blueprint $table) {
            $table->foreignId('pendaftaran_id')
                ->nullable()
                ->unique()
                ->constrained('pendaftarans')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok__darahs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pendaftaran_id');
        });

        Schema::table('riwayat__donors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pendaftaran_id');
        });
    }
};
