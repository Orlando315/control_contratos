<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfesionContactoToEmpleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
          $table->string('profesion')->nullable()->after('talla_pantalon');
          $table->string('nombre_emergencia')->nullable()->after('profesion');
          $table->string('telefono_emergencia')->nullable()->after('nombre_emergencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn(['profesion', 'nombre_emergencia', 'telefono_emergencia']);
        });
    }
}
