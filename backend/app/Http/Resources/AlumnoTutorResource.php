<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *   schema="AlumnoTutorResource",
 *   title="Resource Tutor dentro de Alumno",
 *   description="Este Resource se utiliza únicamente dentro del AlumnoResource y, por tanto, se puede considerar como información duplicada",
 *   type="object",
 *   example={
 *      "nombre": "Ana",
 *      "primer_apellido": "Garcia",
 *      "segundo_apellido": "Perez",
 *      "correo_institucional": "agarciap005@profesor.uaemex.mx"
 *   }
 * )
 *
 */

class AlumnoTutorResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */

  /**
   *
   * @OA\Property(
   *    property="nombre",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *     property="primer_apellido",
   *     type="string"
   * ),
   *
   *
   * @OA\Property(
   *     property="segundo_apellido",
   *     type="string"
   * ),
   *
   *
   * @OA\Property(
   *     property="correo_institucional",
   *     type="string"
   * )
   *
   */

  public function toArray(Request $request): array
  {
    return [
      'nombre' => $this->nombre,
      'primer_apellido' => $this->primer_apellido,
      'segundo_apellido' => $this->segundo_apellido,
      'correo_institucional' => $this->correo_institucional
    ];
  }
}
