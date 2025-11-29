<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** 
 * @OA\Schema(
 *   schema="Materia",
 *   title="Modelo Materia",
 *   type="object",
 *   example={
 *      "clave": "LINC20",
 *      "nombre": "Bases de Datos II",
 *      "nucleo": "Sustantivo",
 *      "caracter": "Obligatoria",
 *      "creditos": 6,
 *      "horas_teoricas": 2,
 *      "horas_practicas": 2
 *   },
 *   @OA\Property(property="clave", type="string"),
 *   @OA\Property(property="nombre", type="string"),
 *   @OA\Property(property="nucleo", type="string"),
 *   @OA\Property(property="caracter", type="string"),
 *   @OA\Property(property="creditos", type="integer"),
 *   @OA\Property(property="horas_teoricas", type="float"),
 *   @OA\Property(property="horas_practicas", type="float")
 * )
 */
class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'clave',
        'nombre',
        'creditos',
        'horas_teoricas',
        'horas_practicas'
    ];

    public function nucleo()
    {
        return $this->belongsTo(Nucleo::class);
    }

    public function caracter()
    {
        return $this->belongsTo(Caracter::class);
    }

    public function licenciaturas()
    {
        return $this->belongsToMany(Licenciatura::class, 'trayectorias')->using(Trayectoria::class);
    }

    public function planEstudios()
    {
        return $this->belongsToMany(PlanEstudio::class, 'trayectorias')->using(Trayectoria::class);
    }

    public function equivalencias(): HasMany
    {
        return $this->hasMany(Equivalencia::class);
    }
}
