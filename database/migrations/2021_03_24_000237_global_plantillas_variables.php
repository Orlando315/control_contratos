<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GlobalPlantillasVariables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plantillas_variables', function (Blueprint $table) {
            $table->unsignedInteger('empresa_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plantillas_variables', function (Blueprint $table) {
            $table->unsignedInteger('empresa_id')->nullable(false)->change();
        });
    }
}
