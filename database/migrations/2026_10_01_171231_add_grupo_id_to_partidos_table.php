<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('partidos', function (Blueprint $table) {

        if (!Schema::hasColumn('partidos', 'grupo_id')) {
            $table->unsignedBigInteger('grupo_id')->after('equipoB_id');
        }

        // agregar FK solo si no existe
        try {
            $table->foreign('grupo_id')
                  ->references('id')
                  ->on('grupos')
                  ->onDelete('cascade');
        } catch (\Exception $e) {
            // ya existe, no hacer nada
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropForeign(['grupo_id']);
            $table->dropColumn('grupo_id');
        });
    }
};
