
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cambios_marcador_pendientes', function (Blueprint $table) {
            $table->id();

            // Partido cuyo marcador fue actualizado
            $table->unsignedBigInteger('partido_id');

            // Subcategoría a la que pertenece el partido
            $table->unsignedBigInteger('subcategoria_id')->index();

            // Control del envío de la notificación
            $table->boolean('notificado')->default(false);

            $table->timestamps();

            $table->foreign('partido_id')
                ->references('id')
                ->on('partidos')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cambios_marcador_pendientes');
    }
};
