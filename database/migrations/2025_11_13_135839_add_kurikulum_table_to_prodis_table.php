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
        Schema::table('prodis', function (Blueprint $table) {
            $table->json('kurikulum_data')->nullable()->after('kurikulum_embed');
            $table->integer('total_sks')->nullable()->after('kurikulum_data');
            $table->integer('jumlah_semester')->default(8)->after('total_sks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->dropColumn(['kurikulum_data', 'total_sks', 'jumlah_semester']);
        });
    }
};
