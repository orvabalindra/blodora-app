<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('status')->default('menunggu_screening')->change();
        });

        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->foreignId('pendaftaran_id')
                ->nullable()
                ->after('pendonor_id')
                ->constrained('pendaftarans')
                ->nullOnDelete();
            $table->foreignId('screened_by')
                ->nullable()
                ->after('pendaftaran_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        DB::table('pendaftarans')
            ->whereIn('status', ['ditunda', 'dibatalkan', 'tidak_hadir'])
            ->update(['status' => 'gagal_screening']);

        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('screened_by');
            $table->dropConstrainedForeignId('pendaftaran_id');
        });

        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu_screening',
                'gagal_screening',
                'proses_donor',
                'selesai',
            ])->default('menunggu_screening')->change();
        });
    }
};
