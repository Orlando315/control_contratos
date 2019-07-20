<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMesPagoInEmpleadosSueldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados_sueldos', function (Blueprint $table) {
          $table->timestamp('mes_pago')->nullable()->after('recibido');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados_sueldos', function (Blueprint $table) {
          $table->dropColumn('mes_pago');
        });
    }
}
