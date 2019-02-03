<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignTransporteConsumosToContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportes_consumos', function (Blueprint $table) {
            $table->unsignedInteger('contrato_id')->nullable()->after('transporte_id');
            $table->foreign('contrato_id')->nullable()->references('id')->on('contratos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transportes_consumos', function (Blueprint $table) {
          $table->dropForeign(['contrato_id']);
          $table->dropColumn('contrato_id');
        });
    }
}
