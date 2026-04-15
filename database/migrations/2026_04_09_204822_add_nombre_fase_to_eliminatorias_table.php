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
$table->string('nombre_fase')->nullable()->after('subcategoria_id');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eliminatorias', function (Blueprint $table) {
           $table->dropColumn('nombre_fase');
        });
    }
};
