<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->string('nama_fakultas')->nullable()->after('nama_prodi');
        });
    }

    public function down()
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->dropColumn('nama_fakultas');
        });
    }
};
