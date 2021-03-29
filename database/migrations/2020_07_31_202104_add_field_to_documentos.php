<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('mime', 100)->nullable()->change();
            $table->unsignedInteger('requisito_id')->nullable()->after('documentable_id');
            $table->foreign('requisito_id')->references('id')->on('requisitos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('mime', 100)->nullable(false)->change();
            $table->dropForeign(['requisito_id']);
            $table->dropColumn('requisito_id');
        });
    }
}
