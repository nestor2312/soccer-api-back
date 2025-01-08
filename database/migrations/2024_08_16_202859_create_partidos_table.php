<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipoA_id');
            $table->foreign('equipoA_id')->references('id')->on('equipos');
            $table->integer('marcador1');
            $table->integer('marcador2');
            $table->unsignedBigInteger('equipoB_id');
            $table->foreign('equipoB_id')->references('id')->on('equipos');    
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
        Schema::dropIfExists('partidos');
    }
}
