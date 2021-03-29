<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequerimientosMaterialesProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requerimientos_materiales_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requerimiento_id');
            $table->foreign('requerimiento_id')->references('id')->on('requerimientos_materiales')->onDelete('cascade');
            $table->unsignedInteger('inventario_id')->nullable()->comment('Inventario V2');
            $table->foreign('inventario_id')->references('id')->on('inventarios_v2')->onDelete('set null');
            $table->string('nombre')->nullable();
            $table->float('cantidad', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requerimientos_materiales_productos');
    }
}
