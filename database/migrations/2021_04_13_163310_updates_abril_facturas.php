<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesAbrilFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->unsignedInteger('faena_id')->nullable()->after('user_id');
            $table->foreign('faena_id')->references('id')->on('faenas')->onDelete('set null');
            $table->unsignedInteger('centro_costo_id')->nullable()->after('faena_id');
            $table->foreign('centro_costo_id')->references('id')->on('centro_costos')->onDelete('set null');
            $table->unsignedInteger('proveedor_id')->nullable()->after('centro_costo_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropforeign(['faena_id']);
            $table->dropforeign(['centro_costo_id']);
            $table->dropforeign(['proveedor_id']);

            $table->dropColumn([
              'faena_id',
              'centro_costo_id',
              'proveedor_id',
            ]);
        });
    }
}
