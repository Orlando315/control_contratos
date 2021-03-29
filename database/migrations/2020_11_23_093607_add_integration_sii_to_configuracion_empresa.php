<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntegrationSiiToConfiguracionEmpresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracion_empresas', function (Blueprint $table) {
            $table->string('sii_clave')->nullable()->after('dias_vencimiento');
            $table->string('sii_clave_certificado')->nullable()->after('sii_clave');
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
            $table->dropColumn(['sii_clave', 'sii_clave_certificado']);
        });
    }
}
