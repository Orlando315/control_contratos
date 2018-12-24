<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnticiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anticipos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->unsignedInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->date('fecha');
            $table->float('anticipo', 20, 2);
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
        Schema::dropIfExists('anticipos');
    }
}
