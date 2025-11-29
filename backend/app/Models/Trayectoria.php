<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** 
 * @OA\Schema(
 *   schema="Trayectoria",
 *   title="Pivote Trayectoria",
 *   type="object",
 *   example={
 *    "materia_id": 1,
 *    "licenciatura_id": 1,
 *    "plan_estudio_id": 1
 *   },
 *   @OA\Property(property="materia_id", type="integer"),
 *   @OA\Property(property="licenciatura_id", type="integer"),
 *   @OA\Property(property="plan_estudio_id", type="integer")
 * )
 */
class Trayectoria extends Model
{
    protected $table = 'trayectorias';

    public $incrementing = true;
}
