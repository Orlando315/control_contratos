<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventariosBelongsToManyBodegasUbicaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios_bodegas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventario_id')->nullable();
            $table->foreign('inventario_id')->references('id')->on('inventarios_v2')->onDelete('cascade');
            $table->unsignedInteger('bodega_id');
            $table->foreign('bodega_id')->references('id')->on('bodegas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('inventarios_ubicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventario_id')->nullable();
            $table->foreign('inventario_id')->references('id')->on('inventarios_v2')->onDelete('cascade');
            $table->unsignedInteger('ubicacion_id');
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('inventarios_v2', function (Blueprint $table) {
            $table->dropForeign(['bodega_id']);
            $table->dropForeign(['ubicacion_id']);

            $table->dropColumn(['bodega_id', 'ubicacion_id']);
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
            $table->unsignedInteger('bodega_id')->nullable()->after('unidad_id');
            $table->foreign('bodega_id')->references('id')->on('bodegas')->onDelete('set null');
            $table->unsignedInteger('ubicacion_id')->nullable()->after('bodega_id');
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones')->onDelete('set null');
        });
        Schema::dropIfExists('inventarios_bodegas');
        Schema::dropIfExists('inventarios_ubicaciones');
    }
}
