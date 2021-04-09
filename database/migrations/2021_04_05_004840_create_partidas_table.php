<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('contrato_id')->nullable();
            $table->foreign('contrato_id')->references('id')->on('contratos')->onDelete('cascade');
            $table->string('tipo');
            $table->string('codigo');
            $table->string('descripcion')->nullable();
            $table->float('monto', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::table('ordenes_compras', function (Blueprint $table) {
            $table->unsignedInteger('partida_id')->nullable()->after('proveedor_id');
            $table->foreign('partida_id')->references('id')->on('partidas')->onDelete('set null');
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->unsignedInteger('partida_id')->nullable()->after('contrato_id');
            $table->foreign('partida_id')->references('id')->on('partidas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_compras', function (Blueprint $table) {
            $table->dropForeign(['partida_id']);
            $table->dropColumn('partida_id');
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['partida_id']);
            $table->dropColumn('partida_id');
        });

        Schema::dropIfExists('partidas');
    }
}
