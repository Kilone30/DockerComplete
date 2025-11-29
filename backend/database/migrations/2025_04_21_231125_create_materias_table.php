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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('clave')->unique();
            $table->string('nombre');
            $table->foreignId('nucleo_id')->constrained('nucleos')->onDelete('cascade');
            $table->foreignId('caracter_id')->constrained('caracteres')->onDelete('cascade');
            $table->integer('creditos');
            $table->float('horas_teoricas');
            $table->float('horas_practicas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
