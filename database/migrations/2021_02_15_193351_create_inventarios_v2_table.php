<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios_v2', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('unidad_id');
            $table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->string('nombre');
            $table->longText('descripcion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('foto')->nulable();
            $table->float('cantidad', 12, 2)->default(0);
            $table->float('stock_minimo', 12, 2)->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('inventarios_v2');
    }
}
