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
         Schema::table('equipos', function (Blueprint $table) {
        $table->dropForeign(['grupo_id']); // si tiene FK
        $table->dropColumn('grupo_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
             $table->unsignedBigInteger('grupo_id')->nullable();
        });
    }
};
