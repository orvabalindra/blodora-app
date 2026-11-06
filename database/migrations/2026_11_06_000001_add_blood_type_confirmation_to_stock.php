<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok__darahs', function (Blueprint $table): void {
            $table->foreignId('golongan_darah_dikonfirmasi_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('golongan_darah_dikonfirmasi_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('stok__darahs', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('golongan_darah_dikonfirmasi_oleh');
            $table->dropColumn('golongan_darah_dikonfirmasi_pada');
        });
    }
};
