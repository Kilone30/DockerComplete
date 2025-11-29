<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="DocenteResource",
 *   title="Resource Docente",
 *   type="object",
 *   example= {
 *    "rfc": "GAP850312C48A",
 *    "nombre": "Ana",
 *    "primer_apellido": "García",
 *    "segundo_apellido": "Pérez",
 *    "correo_institucional": "agarciap005@profesor.uaemex.mx",
 *    "curp": "GAPX850312MDFRRNA3",
 *    "genero": "F",
 *    "tutor": true
 *   },
 *   @OA\Property(
 *     property="rfc",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="nombre",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="primer_apellido",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="segundo_apellido",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="correo_institucional",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="curp",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="genero",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="tutor",
 *     type="boolean"
 *   )
 *  )
 */

class DocenteResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'rfc' => $this->rfc,
      'nombre' => $this->nombre,
      'primer_apellido' => $this->primer_apellido,
      'segundo_apellido' => $this->segundo_apellido,
      'correo_institucional' => $this->correo_institucional,
      'curp' => $this->curp,
      'genero' => $this->genero,
      'tutor' => $this->tutor
    ];
  }
}
