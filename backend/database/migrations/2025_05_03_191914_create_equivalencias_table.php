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
        Schema::create('equivalencias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('materia_id');
            $table->unsignedBigInteger('equivalente_id');

            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            $table->foreign('equivalente_id')->references('id')->on('materias')->onDelete('cascade');

            $table->unique(['materia_id', 'equivalente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equivalencias');
    }
};
