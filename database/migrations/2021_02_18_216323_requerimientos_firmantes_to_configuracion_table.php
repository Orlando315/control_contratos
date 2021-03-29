<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RequerimientosFirmantesToConfiguracionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracion_empresas', function (Blueprint $table) {
            $table->json('requerimientos_firmantes')->nullable()->after('covid19');
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
          $table->dropColumn('requerimientos_firmantes');
        });
    }
}
