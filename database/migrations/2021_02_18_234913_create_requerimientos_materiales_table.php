<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequerimientosMaterialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requerimientos_materiales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('solicitante');
            $table->foreign('solicitante')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('contrato_id')->nullable();
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->unsignedInteger('faena_id')->nullable();
            $table->foreign('faena_id')->references('id')->on('faenas')->onDelete('cascade');
            $table->unsignedInteger('centro_costo_id')->nullable();
            $table->foreign('centro_costo_id')->references('id')->on('centro_costos')->onDelete('cascade');
            $table->unsignedInteger('dirigido')->nullable();
            $table->foreign('dirigido')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('requerimientos_materiales');
    }
}
