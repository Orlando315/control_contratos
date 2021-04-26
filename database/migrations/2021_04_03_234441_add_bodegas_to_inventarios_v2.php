<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBodegasToInventariosV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventarios_v2', function (Blueprint $table) {
            $table->unsignedInteger('bodega_id')->nullable()->after('unidad_id');
            $table->foreign('bodega_id')->references('id')->on('bodegas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventarios_v2', function (Blueprint $table) {
            $table->dropForeign(['bodega_id']);
            $table->dropColumn('bodega_id');
        });
    }
}
