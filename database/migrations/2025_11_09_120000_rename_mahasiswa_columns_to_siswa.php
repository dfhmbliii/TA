<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adjust foreign keys in spk_results: mahasiswa_id -> siswa_id
        if (Schema::hasTable('spk_results')) {
            // Drop possible existing FKs by conventional names (ignore failures)
            try { DB::statement('ALTER TABLE spk_results DROP FOREIGN KEY spk_results_mahasiswa_id_foreign'); } catch (\Throwable $e) {}
            try { DB::statement('ALTER TABLE spk_results DROP FOREIGN KEY spk_results_siswa_id_foreign'); } catch (\Throwable $e) {}

            // Rename column if mahasiswa_id exists and siswa_id does not
            if (Schema::hasColumn('spk_results', 'mahasiswa_id') && !Schema::hasColumn('spk_results', 'siswa_id')) {
                // Requires doctrine/dbal for renameColumn; attempt raw SQL fallback
                try {
                    Schema::table('spk_results', function (Blueprint $table) {
                        $table->renameColumn('mahasiswa_id', 'siswa_id');
                    });
                } catch (\Throwable $e) {
                    // Raw SQL fallback for MySQL
                    DB::statement('ALTER TABLE spk_results CHANGE mahasiswa_id siswa_id BIGINT UNSIGNED NOT NULL');
                }
            }

            // Recreate FK pointing to 'siswas' if available, otherwise to 'mahasiswas'
            if (Schema::hasColumn('spk_results', 'siswa_id')) {
                Schema::table('spk_results', function (Blueprint $table) {
                    if (Schema::hasTable('siswas')) {
                        $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
                    } elseif (Schema::hasTable('mahasiswas')) {
                        $table->foreign('siswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop FK on siswa_id
        if (Schema::hasTable('spk_results')) {
            try { DB::statement('ALTER TABLE spk_results DROP FOREIGN KEY spk_results_siswa_id_foreign'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('spk_results', 'siswa_id') && !Schema::hasColumn('spk_results', 'mahasiswa_id')) {
                try {
                    Schema::table('spk_results', function (Blueprint $table) {
                        $table->renameColumn('siswa_id', 'mahasiswa_id');
                    });
                } catch (\Throwable $e) {
                    DB::statement('ALTER TABLE spk_results CHANGE siswa_id mahasiswa_id BIGINT UNSIGNED NOT NULL');
                }
            }

            // Restore FK to mahasiswas if table exists, otherwise siswas
            if (Schema::hasColumn('spk_results', 'mahasiswa_id')) {
                Schema::table('spk_results', function (Blueprint $table) {
                    if (Schema::hasTable('mahasiswas')) {
                        $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
                    } elseif (Schema::hasTable('siswas')) {
                        $table->foreign('mahasiswa_id')->references('id')->on('siswas')->onDelete('cascade');
                    }
                });
            }
        }

        // No table rename in this option
    }
};
