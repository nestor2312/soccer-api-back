<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePartidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partidos', function (Blueprint $table) {
            // Modificar los marcadores para que permitan valores nulos
            $table->integer('marcador1')->nullable()->change();
            $table->integer('marcador2')->nullable()->change();

            // Agregar nuevas columnas
            $table->date('fecha')->nullable(); 
            $table->time('hora')->nullable();
        });
    }

    public function down()
    {
        Schema::table('partidos', function (Blueprint $table) {
            // Revertir los cambios en marcador1 y marcador2 (eliminar nullable)
            $table->integer('marcador1')->nullable(false)->change();
            $table->integer('marcador2')->nullable(false)->change();

            // Eliminar las nuevas columnas
            $table->dropColumn('fecha');
            $table->dropColumn('hora');
        });
    }
}
