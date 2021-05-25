<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DocumentsAndFoldersVisibilityToEmpleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->boolean('visibilidad')->default(false)->comment('Visibilidad para Empleados')->after('vencimiento');
        });

        Schema::table('carpetas', function (Blueprint $table) {
            $table->boolean('visibilidad')->default(false)->comment('Visibilidad para Empleados')->after('nombre');
        });

        Schema::table('plantillas_documentos', function (Blueprint $table) {
            $table->boolean('visibilidad')->default(false)->comment('Visibilidad para Empleados')->after('secciones');
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
            $table->dropColumn('visibilidad');
        });

        Schema::table('carpetas', function (Blueprint $table) {
            $table->dropColumn('visibilidad');
        });

        Schema::table('plantillas_documentos', function (Blueprint $table) {
            $table->dropColumn('visibilidad');
        });
    }
}
