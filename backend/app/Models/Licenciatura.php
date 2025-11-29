<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** 
 * @OA\Schema(
 *   schema="Licenciatura",
 *   title="Modelo Licenciatura",
 *   type="object",
 *   example={
 *      "clave": "ICO",
 *      "nombre": "Ingeniería en Computación"
 *   },
 *   @OA\Property(property="clave", type="string"),
 *   @OA\Property(property="nombre", type="string")
 * )
 */
class Licenciatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'clave',
        'nombre'
    ];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'trayectorias')->using(Trayectoria::class);
    }

    public function plan_estudios()
    {
        return $this->belongsToMany(PlanEstudio::class, 'trayectorias')->using(Trayectoria::class);
    }

    public function tutoria_equipos()
    {
        return $this->hasMany(TutoriaEquipo::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}
