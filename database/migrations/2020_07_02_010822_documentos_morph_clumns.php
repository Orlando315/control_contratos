<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentosMorphClumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
          $table->string('documentable_type')->nullable()->after('carpeta_id');
          $table->unsignedBigInteger('documentable_id')->nullable()->after('documentable_type');

          $table->index(['documentable_type', 'documentable_id']);
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
          $table->dropIndex(['documentable_type', 'documentable_id']);
          $table->dropColumn(['documentable_type', 'documentable_id']);
        });
    }
}
