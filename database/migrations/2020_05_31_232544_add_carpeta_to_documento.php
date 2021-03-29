<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCarpetaToDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
          $table->unsignedInteger('carpeta_id')->nullable()->after('empleado_id');
          $table->foreign('carpeta_id')->references('id')->on('carpetas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos', function (Blueprint $table) {
          $table->dropForeign(['carpeta_id']);
          $table->dropColumn('carpeta_id');
        });
    }
}
