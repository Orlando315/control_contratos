<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesMarzoOrdenesComprasProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_compras_productos', function (Blueprint $table) {
            $table->boolean('afecto_iva')->default(true)->after('precio');
            $table->float('precio_total', 12, 2)->default(0)->after('impuesto_adicional');

            $table->dropForeign(['inventario_id']);
            $table->foreign('inventario_id')->references('id')->on('inventarios_v2')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_compras_productos', function (Blueprint $table) {
            $table->dropColumn(['afecto_iva', 'precio_total']);
            
            $table->dropForeign(['inventario_id']);
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('set null');
        });
    }
}
