<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSerieToAnticipos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anticipos', function (Blueprint $table) {
            $table->string('serie')->nullable()->after('empleado_id');
            $table->index('serie');
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
            $table->dropIndex(['serie']);
            $table->dropColumn('serie');
        });
    }
}
