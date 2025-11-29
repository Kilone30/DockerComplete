<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** 
 * @OA\Schema(
 *   schema="TutorEquipos",
 *   title="Pivote Tutor Equipos",
 *   type="object",
 *   example={
 *    "docente_id": 1,
 *    "equipo_tutoria_id": 1
 *   },
 *   @OA\Property(property="docente_id", type="integer"),
 *   @OA\Property(property="equipo_tutoria_id", type="integer")
 * )
 */
class TutorEquipo extends Model
{
    protected $table = 'tutor_equipos';

    public $incrementing = true;
}
