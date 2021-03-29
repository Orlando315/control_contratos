<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantillasSeccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantillas_secciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plantilla_id');
            $table->foreign('plantilla_id')->references('id')->on('plantillas')->onDelete('cascade');
            $table->string('nombre')->nullable();
            $table->longText('contenido');
            $table->json('variables')->nullable();
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
        Schema::dropIfExists('plantillas_secciones');
    }
}
