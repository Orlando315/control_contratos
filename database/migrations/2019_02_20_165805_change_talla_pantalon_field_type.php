<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTallaPantalonFieldType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Empleados', function (Blueprint $table) {
          $table->string('talla_pantalon', 5)->nullable()->change();
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
          $table->smallInteger('talla_pantalon')->nullable()->change();
        });
    }
}
