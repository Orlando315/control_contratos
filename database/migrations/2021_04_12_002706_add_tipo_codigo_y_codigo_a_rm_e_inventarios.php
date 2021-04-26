<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoCodigoYCodigoARmEInventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requerimientos_materiales_productos', function (Blueprint $table) {
            $table->string('tipo_codigo')->nullable()->after('inventario_id');
            $table->string('codigo')->nullable()->after('tipo_codigo');
        });

        Schema::table('inventarios_v2', function (Blueprint $table) {
            $table->string('tipo_codigo')->nullable()->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requerimientos_materiales_productos', function (Blueprint $table) {
            $table->dropColumn(['tipo_codigo', 'codigo']);
        });

        Schema::table('inventarios_v2', function (Blueprint $table) {
            $table->dropColumn(['tipo_codigo']);
        });
    }
}
