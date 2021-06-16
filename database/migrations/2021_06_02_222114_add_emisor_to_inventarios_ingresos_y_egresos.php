<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmisorToInventariosIngresosYEgresos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventarios_ingresos', function (Blueprint $table) {
            $table->unsignedInteger('emisor')->nullable()->comment('Usuario emisor')->after('empresa_id');
            $table->foreign('emisor')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('inventarios_egresos', function (Blueprint $table) {
            $table->unsignedInteger('emisor')->nullable()->comment('Usuario emisor')->after('empresa_id');
            $table->foreign('emisor')->references('id')->on('users')->onDelete('cascade');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventarios_ingresos', function (Blueprint $table) {
            $table->dropForeign(['emisor']);
            $table->dropColumn('emisor');
        });

        Schema::table('inventarios_egresos', function (Blueprint $table) {
            $table->dropForeign(['emisor']);
            $table->dropColumn('emisor');
        });
    }
}
