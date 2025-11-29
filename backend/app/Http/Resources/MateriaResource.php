<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *   schema="MateriaResource",
 *   title="Resource Materia",
 *   type="object",
 *   example={
 *     "clave": "LINC20",
 *     "nombre": "Bases de Datos I",
 *     "nucleo": "Sustantivo",
 *     "caracter": "Obligatoria",
 *     "creditos": 6,
 *     "horas_teoricas": 2,
 *     "horas_practicas": 2
 *   },
 *   @OA\Property(
 *     property="clave",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="nombre",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="nucleo",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="caracter",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="creditos",
 *     type="float"
 *   ),
 *   @OA\Property(
 *     property="horas_teoricas",
 *     type="float"
 *   ),
 *   @OA\Property(
 *     property="horas_practicas",
 *     type="float"
 *   )
 * )
 */
class MateriaResource extends JsonResource
{
  public function toArray(Request $request)
  {
    return [
      'clave' => $this->clave,
      'nombre' => $this->nombre,
      'nucleo' => $this->nucleo->nombre ?? null,
      'caracter' => $this->caracter->nombre ?? null,
      'creditos' => (int) $this->creditos,
      'horas_teoricas' => (float) $this->horas_teoricas,
      'horas_practicas' => (float) $this->horas_practicas,
    ];
  }
}
