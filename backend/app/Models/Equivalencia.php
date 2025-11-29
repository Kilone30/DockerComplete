<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 * @OA\Schema(
 *   schema="Equivalencia",
 *   title="Modelo equivalencia",
 *   type="object",
 *   example={
 *      "equivalencia": "IIAT01"
 *   },
 *   @OA\Property(property="equivalencia",type="string")
 * )
 */
class Equivalencia extends Model
{
    public function materia_id(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function equivalente_id(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'equivalente_id');
    }
}
