<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesTransportesAbril2021 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportes', function (Blueprint $table) {
            $table->string('vehiculo')->nullable(true)->change();
            $table->unsignedInteger('user_id')->nullable(true)->change();
            $table->string('modelo')->nullable()->after('patente');
            $table->string('marca')->nullable()->after('modelo');
            $table->string('color')->nullable()->after('marca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transportes', function (Blueprint $table) {
            $table->string('vehiculo')->nullable(false)->change();
            $table->string('user_id')->nullable(false)->change();
            $table->dropColumn(['modelo', 'marca', 'color']);
        });
    }
}
