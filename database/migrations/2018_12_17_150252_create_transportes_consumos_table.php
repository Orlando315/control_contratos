<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportesConsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportes_consumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transporte_id');
            $table->foreign('transporte_id')->references('id')->on('transportes')->onDelete('cascade');
            $table->tinyInteger('tipo')->comment('1 Mantenimiento|2 Combustible');
            $table->date('fecha');
            $table->float('cantidad', 20, 2)->comment('cantidad de combustible')->nullable();
            $table->float('valor', 20, 2);
            $table->string('chofer');
            $table->string('observacion')->nullable();
            $table->string('adjunto')->nullable();
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
        Schema::dropIfExists('transportes_consumos');
    }
}
