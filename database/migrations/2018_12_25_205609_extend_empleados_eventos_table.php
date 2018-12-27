<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendEmpleadosEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados_eventos', function (Blueprint $table) {
          $table->unsignedInteger('reemplazo')->nullable()->comment('Solo en reemplazo')->after('empleado_id');
          $table->foreign('reemplazo')->references('id')->on('empleados')->onDelete('cascade');
          $table->float('valor', 20, 2)->nullable()->comment('Solo en reemplazo')->after('reemplazo');
          $table->string('jornada')->after('tipo');
          $table->boolean('comida')->default(false)->after('jornada');
          $table->boolean('pago')->default(false)->after('comida');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados_eventos', function (Blueprint $table) {
          $table->dropForeign (['reemplazo']);
          $table->dropColumn(['reemplazo', 'valor', 'jornada', 'comida']);
        });
    }
}
