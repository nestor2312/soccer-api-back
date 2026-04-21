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
        Schema::table('eventos_partido', function (Blueprint $table) {

        // Nueva relación para eliminatorias
        $table->foreignId('eliminatoria_id')
            ->nullable()
            ->after('partido_id')
            ->constrained('eliminatorias')
            ->nullOnDelete();

        // Ampliar tipo_evento

$table->string('tipo_evento', 50)->change();

        $table->foreignId('partido_id')
    ->nullable()
    ->change();

        // Contexto del evento
        $table->enum('instancia', [
            'normal',
            'ida',
            'vuelta',
            'tanda_penales'
        ])->default('normal')->after('tipo_evento');
    });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('eventos_partido', function (Blueprint $table) {

        $table->dropColumn('instancia');
        $table->dropForeign(['eliminatoria_id']);
        $table->dropColumn('eliminatoria_id');
        $table->foreignId('partido_id')
            ->nullable(false)
            ->change();
    });
}
};
