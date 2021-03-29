<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CarpetaAsRequisito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisitos', function (Blueprint $table) {
            $table->boolean('folder')->default(false)->after('type');
        });

        Schema::table('carpetas', function (Blueprint $table) {
            $table->unsignedInteger('requisito_id')->nullable()->after('carpeta_id');
            $table->foreign('requisito_id')->references('id')->on('requisitos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisitos', function (Blueprint $table) {
            $table->dropColumn('folder');
        });

        Schema::table('carpetas', function (Blueprint $table) {
            $table->dropForeign(['requisito_id']);
            $table->dropColumn('requisito_id');
        });
    }
}
