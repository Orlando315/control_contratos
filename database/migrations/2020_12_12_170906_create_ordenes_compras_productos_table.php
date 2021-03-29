<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesComprasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_compras_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('orden_compra_id');
            $table->foreign('orden_compra_id')->references('id')->on('ordenes_compras')->onDelete('cascade');
            $table->unsignedInteger('inventario_id')->nullable();
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('set null');
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
        Schema::dropIfExists('ordenes_compras_productos');
    }
}
