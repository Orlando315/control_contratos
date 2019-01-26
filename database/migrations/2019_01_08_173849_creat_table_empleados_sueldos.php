<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableEmpleadosSueldos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados_sueldos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->unsignedInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->float('alcance_liquido', 20,2);
            $table->integer('asistencias');
            $table->float('anticipo', 20,2);
            $table->float('bono_reemplazo', 20,2)->comment('Valor tomado de los reemplazos realizados');
            $table->float('sueldo_liquido', 20,2)->comment('Alcance liquido - Anticipo');
            $table->string('adjunto')->nullable();
            $table->boolean('recibido')->default(false);
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
        Schema::dropIfExists('empleados_sueldos');
    }
}
