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
                       $table->string('sede')->nullable();
 $table->date('fecha')->nullable(); 
            $table->time('hora')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eliminatorias', function (Blueprint $table) {
              $table->dropColumn('sede');
        $table->dropColumn(['fecha']);
        $table->dropColumn('hora');
        });
    }
};
