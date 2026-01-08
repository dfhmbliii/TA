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
        Schema::create('prodi_pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade');
            $table->foreignId('alternative_1_id')->constrained('prodi_alternatives')->onDelete('cascade');
            $table->foreignId('alternative_2_id')->constrained('prodi_alternatives')->onDelete('cascade');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
            
            // Ensure unique comparison pair per prodi
            $table->unique(['prodi_id', 'alternative_1_id', 'alternative_2_id'], 'prodi_pairwise_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodi_pairwise_comparisons');
    }
};
