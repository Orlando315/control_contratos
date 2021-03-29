<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PostulantesPlantillasDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plantillas_documentos', function (Blueprint $table) {
            $table->unsignedInteger('postulante_id')->nullable()->after('empleado_id');
            $table->foreign('postulante_id')->references('id')->on('postulantes')->onDelete('cascade');


            $table->unsignedInteger('contrato_id')->nullable(true)->change();
            $table->unsignedInteger('empleado_id')->nullable(true)->change();
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
            $table->dropForeign(['postulante_id']);
            $table->dropColumn('postulante_id');

            $table->unsignedInteger('contrato_id')->nullable(false)->change();
            $table->unsignedInteger('empleado_id')->nullable(false)->change();
        });
    }
}
