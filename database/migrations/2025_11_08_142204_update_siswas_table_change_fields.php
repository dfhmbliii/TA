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
        Schema::table('siswas', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('npm', 'nisn');
            
            // Drop old prodi_id foreign key and column
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
            
            // Add new columns
            $table->string('jurusan_sma')->nullable()->after('email');
            $table->string('asal_sekolah')->nullable()->after('jurusan_sma');
            $table->year('tahun_lulus')->nullable()->after('asal_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Reverse rename columns
            $table->renameColumn('nisn', 'npm');
            
            // Drop new columns
            $table->dropColumn(['jurusan_sma', 'asal_sekolah', 'tahun_lulus']);
            
            // Re-add prodi_id
            $table->unsignedBigInteger('prodi_id')->nullable()->after('email');
            $table->foreign('prodi_id')->references('id')->on('prodis')->nullOnDelete();
        });
    }
};
