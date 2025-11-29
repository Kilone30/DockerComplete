<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="Docente",
 *   title="Modelo Docente",
 *   type="object",
 *   example= {
 *    "rfc": "GAP850312C48A",
 *    "nombre": "Ana",
 *    "primer_apellido": "García",
 *    "segundo_apellido": "Pérez",
 *    "correo_institucional": "agarciap005@profesor.uaemex.mx",
 *    "curp": "GAPX850312MDFRRNA3",
 *    "genero": "F",
 *    "tutor": true
 *   },
 *   @OA\Property(
 *    property="rfc",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="nombre",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="primer_apellido",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="segundo_apellido",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="correo_institucional",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="curp",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="genero",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="tutor",
 *    type="boolean"
 *   )
 *  )
 */
class Docente extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'rfc',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'correo_institucional',
        'curp',
        'genero',
        'tutor'
    ];

    public function tutoria_equipos()
    {
        return $this->hasMany(TutoriaEquipo::class, 'docentes_tutoria_equipos')->using(TutorEquipo::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}
