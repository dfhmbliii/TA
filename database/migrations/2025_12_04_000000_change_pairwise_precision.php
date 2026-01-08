<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Increase precision so we don't lose reciprocal precision
        DB::statement('ALTER TABLE pairwise_comparisons MODIFY nilai DECIMAL(10,6)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE pairwise_comparisons MODIFY nilai DECIMAL(5,2)');
    }
};
