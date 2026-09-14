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
        Schema::table('eliminatorias', function (Blueprint $table) {
           $table->string('tipo_partido_extra')->default('normal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eliminatorias', function (Blueprint $table) {
             $table->dropColumn('tipo_partido_extra');
        });
    }
};
