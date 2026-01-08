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
        Schema::create('pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_1_id')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('kriteria_2_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai', 5, 2); // Nilai perbandingan (1-9 or 1/9 to 1/2)
            $table->timestamps();
            
            // Ensure unique comparison pair
            $table->unique(['kriteria_1_id', 'kriteria_2_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pairwise_comparisons');
    }
};
