<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmaToConfiguracion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracion_empresas', function (Blueprint $table) {
            $table->string('firma')->nullable();
        });

        Schema::table('facturaciones', function (Blueprint $table) {
            $table->dropColumn('firma');
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
            $table->dropColumn('firma');
        });

        Schema::table('facturaciones', function (Blueprint $table) {
            $table->string('firma')->nullable();
        });
    }
}
