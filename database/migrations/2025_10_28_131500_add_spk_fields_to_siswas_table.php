<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('npm')->nullable()->after('name');
            $table->string('email')->nullable()->after('npm');
            $table->unsignedBigInteger('prodi_id')->nullable()->after('email');
            $table->decimal('ipk', 3, 2)->nullable()->after('prodi_id');
            $table->decimal('prestasi_score', 5, 2)->nullable()->after('ipk');
            $table->decimal('kepemimpinan', 5, 2)->nullable()->after('prestasi_score');
            $table->decimal('sosial', 5, 2)->nullable()->after('kepemimpinan');
            $table->decimal('komunikasi', 5, 2)->nullable()->after('sosial');
            $table->decimal('kreativitas', 5, 2)->nullable()->after('komunikasi');
            $table->decimal('spk_last_score', 8, 4)->nullable()->after('kreativitas');
            $table->string('spk_last_category')->nullable()->after('spk_last_score');

            $table->foreign('prodi_id')->references('id')->on('prodis')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            $table->dropColumn([
                'name','npm','email','prodi_id','ipk','prestasi_score','kepemimpinan','sosial','komunikasi','kreativitas','spk_last_score','spk_last_category'
            ]);
        });
    }
};
