<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPlantillasDocumentosForeignPlantillasDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plantillas_documentos', function (Blueprint $table) {
            $table->dropForeign(['documento_id']);
            $table->foreign('documento_id')->references('id')->on('plantillas_documentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plantillas_documentos', function (Blueprint $table) {
            $table->dropForeign(['documento_id']);
            $table->foreign('documento_id')->references('id')->on('documentos')->onDelete('cascade');
        });
    }
}
