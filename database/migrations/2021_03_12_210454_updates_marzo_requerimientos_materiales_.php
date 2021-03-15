<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesMarzoRequerimientosMateriales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requerimientos_materiales', function (Blueprint $table) {
            $table->date('fecha')->nullable()->after('dirigido');
            $table->string('urgencia')->default('normal')->after('fecha');
        });

        Schema::table('requerimientos_materiales_productos', function (Blueprint $table) {
            $table->boolean('added')->default(false)->comment('Si fue agregado por firmante')->after('cantidad');
        });

        Schema::table('requerimientos_materiales_firmantes', function (Blueprint $table) {
            $table->string('observacion')->nullable()->after('obligatorio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requerimientos_materiales', function (Blueprint $table) {
            $table->dropColumn(['fecha', 'urgencia']);
        });

        Schema::table('requerimientos_materiales_productos', function (Blueprint $table) {
            $table->dropColumn('added');
        });

        Schema::table('requerimientos_materiales_firmantes', function (Blueprint $table) {
            $table->dropColumn('observacion');
        });
    }
}
