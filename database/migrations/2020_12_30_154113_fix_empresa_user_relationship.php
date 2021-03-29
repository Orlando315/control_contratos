<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class fixEmpresaUserRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('rut', 15)->nullable()->unique()->after('id');
            $table->string('telefono', 20)->nullable()->after('logo');
            $table->string('email', 50)->nullable()->after('telefono');
            $table->renameColumn('nombres', 'nombre');
        });

        Schema::table('users', function (Blueprint $table) {
          $table->dropForeign(['empresa_id']);
          $table->dropColumn('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['rut', 'telefono', 'email']);
            $table->renameColumn('nombre', 'nombres');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }
}
