<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropConsumosAdjuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('consumos_adjuntos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('consumos_adjuntos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('consumo_id')->nullable();
            $table->foreign('consumo_id')->references('id')->on('transportes_consumos')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('path', 100)->nullable();
            $table->string('mime', 100);
            $table->timestamps();
        });
    }
}
