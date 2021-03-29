<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCovid19ToConfiguracion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracion_empresas', function (Blueprint $table) {
            $table->boolean('covid19')->default(false)->after('terminos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracion_empresas', function (Blueprint $table) {
            $table->dropColumn('covid19');
        });
    }
}
