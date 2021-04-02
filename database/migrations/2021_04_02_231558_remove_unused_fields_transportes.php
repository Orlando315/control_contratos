<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedFieldsTransportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['faena_id']);

            $table->dropColumn(['user_id', 'faena_id']);
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
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('faena_id')->nullable();
            $table->foreign('faena_id')->references('id')->on('faenas')->onDelete('set null');
        });
    }
}
