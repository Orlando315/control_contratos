<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescripcionToEmpleadosContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados_contratos', function (Blueprint $table) {
          $table->string('descripcion')->nullable()->after('inicio_jornada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados_contratos', function (Blueprint $table) {
          $table->dropColumn('descripcion');
        });
    }
}
