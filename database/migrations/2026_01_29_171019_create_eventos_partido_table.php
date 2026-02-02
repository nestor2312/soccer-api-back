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
        Schema::create('eventos_partido', function (Blueprint $table) {
            $table->id();
              $table->foreignId('partido_id')->constrained('partidos')->onDelete('cascade');
        $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
        $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
        $table->enum('tipo_evento', ['gol', 'asistencia', 'amarilla', 'roja']);
        $table->string('minuto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_partido');
    }
};
