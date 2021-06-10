<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixIntregrationFacuracionSii extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracion_empresas', function (Blueprint $table) {
            $table->dropColumn(['sii_clave', 'sii_clave_certificado', 'firma']);
            $table->json('sii_account')->nullable()->after('dias_vencimiento');
            $table->json('sii_representante')->nullable()->after('sii_account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracion_empresas', function (Blueprint $table) {
            $table->dropColumn(['sii_account', 'sii_representante']);
            $table->string('sii_clave')->nullable()->after('dias_vencimiento');
            $table->string('sii_clave_certificado')->nullable()->after('sii_clave');
            $table->string('firma')->nullable()->after('sii_clave_certificado');
        });
    }
}
