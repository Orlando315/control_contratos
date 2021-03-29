<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAdjuntoFromTransportesConsumos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportes_consumos', function (Blueprint $table) {
          $table->dropColumn('adjunto');
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
          $table->string('adjunto')->nullable();
        });
    }
}
