<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cotizacion_id');
            $table->foreign('cotizacion_id')->references('id')->on('cotizaciones')->onDelete('cascade');
            $table->unsignedInteger('inventario_id')->nullable();
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('cascade');
            $table->string('tipo_codigo')->nullable();
            $table->string('codigo')->nullable();
            $table->string('nombre')->nullable();
            $table->float('cantidad', 8, 2);
            $table->float('precio', 12, 2);
            $table->float('impuesto_adicional', 12, 2)->nullable();
            $table->float('total', 12, 2);
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('cotizaciones_productos');
    }
}
