<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportesFaenasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportes_faenas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transporte_id');
            $table->foreign('transporte_id')->references('id')->on('transportes')->onDelete('cascade');
            $table->unsignedInteger('faena_id');
            $table->foreign('faena_id')->references('id')->on('faenas')->onDelete('cascade');
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
        Schema::dropIfExists('transportes_faenas');
    }
}
