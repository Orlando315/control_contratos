<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequerimientosMaterialesLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requerimientos_materiales_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requerimiento_id');
            $table->foreign('requerimiento_id')->references('id')->on('requerimientos_materiales')->onDelete('cascade');
            $table->string('type')->default('firmante');
            $table->string('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requerimientos_materiales_logs');
    }
}
