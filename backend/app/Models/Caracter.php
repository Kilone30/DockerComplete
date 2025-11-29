<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** 
 * @OA\Schema(
 *   schema="Caracter",
 *   title="Modelo Caracter",
 *   type="string",
 *   example="Obligatoria"
 * )
 */
class Caracter extends Model
{
    use HasFactory;

    protected $table = 'caracteres';

    protected $fillable = [
        'nombre'
    ];

    public function materia()
    {
        return $this->hasMany(Materia::class);
    }
}
