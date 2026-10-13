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
        Schema::table('pendonors', function (Blueprint $table) {
            $table->unique('user_id', 'pendonors_user_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendonors', function (Blueprint $table) {
            $table->dropUnique('pendonors_user_id_unique');
        });
    }
};
