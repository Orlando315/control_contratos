<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ArchivosGlobales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carpetas', function (Blueprint $table) {
            $table->string('carpetable_type')->nullable()->change();
            $table->unsignedBigInteger('carpetable_id')->nullable()->change();
            $table->boolean('public')->default(true)->after('visibilidad');
            $table->string('location')->nullable()->after('public');
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->boolean('public')->default(true)->after('visibilidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carpetas', function (Blueprint $table) {
            $table->string('carpetable_type')->nullable(false)->change();
            $table->unsignedBigInteger('carpetable_id')->nullable(false)->change();
            $table->dropColumn(['public', 'location']);
        });

        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('public');
        });
    }
}
