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
        Schema::create('trayectorias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('periodo');

            $table->foreignId('materia_id')->constrained(indexName: 'materias_trayectorias');
            $table->foreignId('licenciatura_id')->constrained(indexName: 'licenciaturas_trayectorias');
            $table->foreignId('plan_estudio_id')->constrained(indexName: 'plan_estudios_trayectorias');

            $table->unique(['materia_id', 'licenciatura_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trayectorias');
    }
};
