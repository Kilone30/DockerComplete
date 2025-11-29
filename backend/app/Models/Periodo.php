<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  schema="Periodo",
 *  title="Modelo Periodo",
 *  type="object",
 *  example= {
 *    "periodo": "2022A",
 *    "fecha_inicio": "2022-02-01",
 *    "fecha_final": "2022-07-15"
 *  },
 *  @OA\Property(
 *     property="periodo",
 *     type="string"
 *  ),
 *  @OA\Property(
 *     property="fecha_inicio",
 *     type="date"
 *  ),
 *  @OA\Property(
 *     property="fecha_final",
 *     type="date"
 *  )
 * )
 */
class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'periodo',
        'fecha_inicio',
        'fecha_final'
    ];

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}
