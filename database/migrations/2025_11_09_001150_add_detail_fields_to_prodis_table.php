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
            $table->longText('visi_misi')->nullable()->after('deskripsi');
            $table->longText('prospek_kerja')->nullable()->after('visi_misi');
            $table->string('visi_misi_url')->nullable()->after('prospek_kerja');
            $table->string('prospek_url')->nullable()->after('visi_misi_url');
            $table->string('kurikulum_url')->nullable()->after('prospek_url');
            $table->boolean('kurikulum_embed')->default(false)->after('kurikulum_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->dropColumn([
                'visi_misi',
                'prospek_kerja',
                'visi_misi_url',
                'prospek_url',
                'kurikulum_url',
                'kurikulum_embed'
            ]);
        });
    }
};
