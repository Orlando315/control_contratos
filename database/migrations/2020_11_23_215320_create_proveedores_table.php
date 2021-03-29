<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
            $table->string('type')->comment('empresa|persona');
            $table->string('nombre');
            $table->string('rut')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('descripcion')->nullable();

            $table->index('nombre');
            $table->index('rut');
            $table->index('email');

            $table->timestamps();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedInteger('proveedor_id')->nullable()->after('empresa_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn('proveedor_id');
        });

        Schema::dropIfExists('proveedores');
    }
}
