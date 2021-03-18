<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRequerimientosGenerateCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_compras', function (Blueprint $table) {
            $table->unsignedInteger('requerimiento_id')->nullable()->after('empresa_id');
            $table->foreign('requerimiento_id')->references('id')->on('requerimientos_materiales')->onDelete('cascade');
        });

        Schema::table('ordenes_compras_productos', function (Blueprint $table) {
            $table->unsignedInteger('producto_id')->nullable()->comment('RM Producto')->after('orden_compra_id');
            $table->foreign('producto_id')->references('id')->on('requerimientos_materiales_productos')->onDelete('cascade');
        });

        Schema::table('contactos', function (Blueprint $table) {
            $table->boolean('status')->default(false)->after('descripcion');
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
            $table->dropForeign(['requerimiento_id']);
            $table->dropColumn('requerimiento_id');
        });
        
        Schema::table('ordenes_compras_productos', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
        });

        Schema::table('contactos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
