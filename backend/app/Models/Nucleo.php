<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** 
 * @OA\Schema(
 *   schema="Nucleo",
 *   title="Modelo Nucleo",
 *   type="string",
 *   example="Sustantivo"
 * )
 */
class Nucleo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    public function materia()
    {
        return $this->hasMany(Materia::class);
    }
}
