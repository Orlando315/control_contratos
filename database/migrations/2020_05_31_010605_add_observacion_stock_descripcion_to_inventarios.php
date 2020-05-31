<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddObservacionStockDescripcionToInventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->integer('low_stock')->nullable()->after('cantidad');
            $table->string('observacion')->nullable()->after('low_stock');
            $table->string('descripcion')->nullable()->after('observacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropColumn(['low_stock', 'observacion', 'descripcion']);
        });
    }
}
