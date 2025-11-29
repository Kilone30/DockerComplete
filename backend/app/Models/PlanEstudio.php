<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** 
 * @OA\Schema(
 *   schema="PlanEstudio",
 *   title="Modelo Plan Estudio",
 *   type="string",
 *   example="F19"
 * )
 */
class PlanEstudio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'fecha_implementacion'
    ];

    public function licenciaturas()
    {
        return $this->belongsToMany(Licenciatura::class, 'trayectorias')->using(Trayectoria::class);
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'trayectorias')->using(Trayectoria::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}
