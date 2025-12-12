<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEliminatoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eliminatorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcategoria_id');
            $table->unsignedBigInteger('equipo_a_id')->nullable();
            $table->unsignedBigInteger('equipo_b_id')->nullable();
            $table->integer('marcador1_ida')->nullable();
            $table->integer('marcador2_ida')->nullable();
            $table->integer('marcador1_vuelta')->nullable();
            $table->integer('marcador2_vuelta')->nullable();
            $table->integer('marcador1_penales')->nullable();
            $table->integer('marcador2_penales')->nullable();
            $table->integer('numPartido');
            $table->enum('tipo_eliminatoria', ['solo_ida', 'ida_vuelta','penales']); // Si el partido es solo de ida o ida y vuelta
            $table->enum('tipo_partido', ['ida', 'vuelta','penales'])->nullable(); // Si es el partido de ida o el de vuelta, en caso de ida y vuelta
            $table->foreign('subcategoria_id')->references('id')->on('subcategories');
            $table->foreign('equipo_a_id')->references('id')->on('equipos')->onDelete('set null');
            $table->foreign('equipo_b_id')->references('id')->on('equipos')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eliminatorias');
    }
}
