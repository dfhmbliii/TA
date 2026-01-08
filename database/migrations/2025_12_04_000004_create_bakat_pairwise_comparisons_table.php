<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bakat_pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_1_id')->constrained('bakat_categories')->cascadeOnDelete();
            $table->foreignId('category_2_id')->constrained('bakat_categories')->cascadeOnDelete();
            $table->decimal('nilai', 10, 6)->default(1);
            $table->timestamps();
            $table->unique(['category_1_id', 'category_2_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bakat_pairwise_comparisons');
    }
};
