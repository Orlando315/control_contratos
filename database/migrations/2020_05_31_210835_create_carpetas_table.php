<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarpetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carpetas', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('empresa_id');
          $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
          $table->morphs('carpetable');
          $table->unsignedInteger('carpeta_id')->nullable();
          $table->foreign('carpeta_id')->references('id')->on('carpetas')->onDelete('cascade');
          $table->string('nombre');
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
        Schema::dropIfExists('carpetas');
    }
}
