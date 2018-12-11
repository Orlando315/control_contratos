<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios_entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventario_id');
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('cascade');
            $table->unsignedInteger('realizado')->comment('Usuario que registro la entrega');
            $table->foreign('realizado')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('entregado')->comment('Usuario a quien se le entrega');
            $table->foreign('entregado')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('cantidad');
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
        Schema::dropIfExists('inventarios_entregas');
    }
}
