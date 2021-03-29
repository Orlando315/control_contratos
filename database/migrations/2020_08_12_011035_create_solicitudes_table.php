<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('empresa_id');
          $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
          $table->unsignedInteger('empleado_id');
          $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
          $table->string('tipo');
          $table->string('otro')->nullable();
          $table->string('descripcion')->nullable();
          $table->string('adjunto')->nullable();
          $table->string('observacion')->nullable();
          $table->unsignedTinyInteger('status')->nullable();
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
        Schema::dropIfExists('solicitudes');
    }
}
