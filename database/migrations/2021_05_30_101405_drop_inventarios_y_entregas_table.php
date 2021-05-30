<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropInventariosYEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('inventarios_entregas');
        Schema::dropIfExists('inventarios');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::create('inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('contrato_id')->nullable();
            $table->foreign('contrato_id')->nullable()->references('id')->on('contratos')->onDelete('cascade');
            $table->tinyInteger('tipo')->comment('1 Insumo|2 Epp');
            $table->string('otro')->nullable()->comment('tipo otro');
            $table->string('nombre');
            $table->float('valor', 20, 2);
            $table->date('fecha');
            $table->unsignedInteger('cantidad');
            $table->integer('low_stock')->nullable();
            $table->string('observacion')->nullable();
            $table->string('descripcion')->nullable();
            $table->boolean('calibracion')->default(false);
            $table->boolean('certificado')->default(false);
            $table->string('adjunto')->nullable();
            $table->timestamps();
        });

        Schema::create('inventarios_entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventario_id');
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('cascade');
            $table->unsignedInteger('realizado')->comment('Usuario que registro la entrega');
            $table->foreign('realizado')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('entregado')->comment('Usuario a quien se le entrega');
            $table->foreign('entregado')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('cantidad');
            $table->string('adjunto')->nullable();
            $table->boolean('recibido')->default(false);
            $table->timestamps();
        });
    }
}
