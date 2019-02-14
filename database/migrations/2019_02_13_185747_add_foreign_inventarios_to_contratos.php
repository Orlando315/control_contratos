<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignInventariosToContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Inventarios', function (Blueprint $table) {
          $table->unsignedInteger('contrato_id')->nullable()->after('empresa_id');
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
        Schema::table('Inventarios', function (Blueprint $table) {
          $table->dropForeign(['contrato_id']);
          $table->dropColumn('contrato_id');
        });
    }
}
