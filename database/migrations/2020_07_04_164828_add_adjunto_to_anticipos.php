<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdjuntoToAnticipos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anticipos', function (Blueprint $table) {
          $table->float('bono', 20, 2)->nullable()->after('anticipo');
          $table->string('descripcion')->nullable()->after('bono');
          $table->string('adjunto')->nullable()->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anticipos', function (Blueprint $table) {
          $table->dropColumn(['bono', 'descripcion', 'adjunto']);
        });
    }
}
