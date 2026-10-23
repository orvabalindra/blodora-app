<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('persetujuan_admin')->default('disetujui')->after('status');
            $table->text('catatan_admin')->nullable()->after('keterangan_medis');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['persetujuan_admin', 'catatan_admin']);
        });
    }
};
