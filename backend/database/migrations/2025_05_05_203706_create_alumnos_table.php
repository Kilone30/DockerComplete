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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->string('num_cuenta')->unique();
            $table->string('nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->enum('genero', ['M', 'F'])->nullable();
            $table->string('correo_personal')->nullable();
            $table->string('correo_institucional')->unique();
            $table->foreignId('licenciatura_id')->constrained('licenciaturas');
            $table->foreignUuid('tutor_id')->nullable()->constrained('docentes');
            $table->foreignId('periodo_ingreso')->nullable()->constrained('periodos');
            $table->foreignId('linea_especializada_id')->nullable()->constrained('linea_especializadas');
            $table->foreignId('tipo_baja_id')->nullable()->constrained('tipo_bajas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
