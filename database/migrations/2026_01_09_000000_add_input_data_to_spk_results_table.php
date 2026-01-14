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
        Schema::table('spk_results', function (Blueprint $table) {
            // Add input_data column if it doesn't exist
            if (!Schema::hasColumn('spk_results', 'input_data')) {
                $table->json('input_data')->nullable()->after('criteria_values');
            }
            
            // Add rekomendasi_prodi column if it doesn't exist
            if (!Schema::hasColumn('spk_results', 'rekomendasi_prodi')) {
                $table->text('rekomendasi_prodi')->nullable()->after('category');
            }
            
            // Add criteria_breakdown column if it doesn't exist
            if (!Schema::hasColumn('spk_results', 'criteria_breakdown')) {
                $table->json('criteria_breakdown')->nullable()->after('rekomendasi_prodi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spk_results', function (Blueprint $table) {
            $table->dropColumn(['input_data', 'rekomendasi_prodi', 'criteria_breakdown']);
        });
    }
};
