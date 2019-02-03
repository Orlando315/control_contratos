<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Empleados', function (Blueprint $table) {
          $table->float('talla_zapato', 3,  1)->nullable()->change();
          $table->smallInteger('talla_pantalon')->nullable()->change();
          $table->smallInteger('talla_camisa')->nullable()->after('direccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Empleados', function (Blueprint $table) {
          $table->dropColumn('talla_camisa');
          $table->float('talla_zapato', 3, 1)->nullable(false)->change();
          $table->smallInteger('talla_pantalon')->nullable(false)->change();
        });
    }
}
