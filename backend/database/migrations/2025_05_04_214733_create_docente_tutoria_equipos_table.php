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
        Schema::create('tutor_equipos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignUuid('docente_id')->constrained(indexName: 'docente_rel');
            $table->foreignId('equipo_tutoria_id')->constrained('tutoria_equipos', indexName: 'equipo_rel');

            $table->unique(['docente_id', 'equipo_tutoria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_equipos');
    }
};
