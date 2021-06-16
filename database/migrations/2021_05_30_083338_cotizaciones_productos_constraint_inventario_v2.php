<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CotizacionesProductosConstraintInventarioV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cotizaciones_productos', function (Blueprint $table) {
            $table->dropForeign(['inventario_id']);
            $table->foreign('inventario_id')->references('id')->on('inventarios_v2')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cotizaciones_productos', function (Blueprint $table) {
            $table->dropForeign(['inventario_id']);
            
            if(Schema::hasTable('inventarios')){
              $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('cascade'); 
            }
        });
    }
}
