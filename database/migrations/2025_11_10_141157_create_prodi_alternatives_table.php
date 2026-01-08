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
        Schema::create('prodi_alternatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade');
            $table->string('nama_alternatif'); // e.g., "Informatika", "Matematika"
            $table->string('kode_alternatif'); // e.g., "INF", "MAT"
            $table->text('deskripsi')->nullable();
            $table->decimal('bobot', 5, 4)->default(0); // Weight dari AHP
            $table->integer('urutan')->default(0);
            $table->timestamps();
            
            // Unique per prodi
            $table->unique(['prodi_id', 'kode_alternatif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodi_alternatives');
    }
};
