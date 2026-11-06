<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(30) NOT NULL");
        } else {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('role', 30)->default('pendonor')->change();
            });
        }

        Schema::table('rumah_sakits', function (Blueprint $table): void {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();
        });

        Schema::table('permintaan__darahs', function (Blueprint $table): void {
            $table->string('tingkat_kebutuhan', 20)->default('normal');
        });
    }

    public function down(): void
    {
        if (DB::table('users')->where('role', 'rumah_sakit')->exists()) {
            throw new RuntimeException('Hapus akun rumah sakit sebelum membatalkan migrasi role rumah sakit.');
        }

        Schema::table('permintaan__darahs', function (Blueprint $table): void {
            $table->dropColumn('tingkat_kebutuhan');
        });

        Schema::table('rumah_sakits', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'pendonor') NOT NULL");
        } else {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('role', 30)->default('pendonor')->change();
            });
        }
    }
};
