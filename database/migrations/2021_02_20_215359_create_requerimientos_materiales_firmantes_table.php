<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequerimientosMaterialesFirmantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requerimientos_materiales_firmantes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requerimiento_id');
            $table->foreign('requerimiento_id')->references('id')->on('requerimientos_materiales')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('texto')->nullable();
            $table->boolean('obligatorio')->default(false);
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('requerimientos_materiales_firmantes');
    }
}
