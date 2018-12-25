<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->tinyInteger('tipo')->comment('1 Ingreso|2 Egreso');
            $table->unsignedInteger('user_id')->comment('Quien agrego la factura');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nombre');
            $table->string('realizada_para');
            $table->string('realizada_por');
            $table->date('fecha');
            $table->float('valor', 20, 2);
            $table->date('pago_fecha');
            $table->boolean('pago_estado')->default(false);
            $table->string('adjunto1')->nullable();
            $table->string('adjunto2')->nullable();
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
        Schema::dropIfExists('facturas');
    }
}
