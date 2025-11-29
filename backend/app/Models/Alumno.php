<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *   schema="Alumno",
 *   title="Modelo Alumno",
 *   type="object",
 *   example= {
 *    "num_cuenta": "2245123",
 *    "nombre": "Salvador",
 *    "primer_apellido": "Benitez",
 *    "segundo_apellido": "Sanchez",
 *    "genero": "M",
 *    "correo_personal": "salvador123@gmail.com",
 *    "correo_institucional": "sbenitezs002@alumno.uaemex.mx"
 *   },
 *   @OA\Property(
 *     property="num_cuenta",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="nombre",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="primer_apellido",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="segundo_apellido",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="genero",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="correo_personal",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="correo_institucional",
 *     type="string"
 *   )
 * )
 *
 */
class Alumno extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'num_cuenta',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'genero',
        'correo_personal',
        'correo_institucional'
    ];

    public $incrementing = false;

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function licenciatura()
    {
        return $this->belongsTo(Licenciatura::class);
    }

    public function plan_estudio()
    {
        return $this->belongsTo(PlanEstudio::class);
    }

    public function linea_especializada()
    {
        return $this->belongsTo(LineaEspecializada::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function tipo_baja()
    {
        return $this->belongsTo(TipoBaja::class);
    }
}
