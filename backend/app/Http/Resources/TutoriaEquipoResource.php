<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *   schema="GrupoTutoriaResource",
 *   title="Resource Grupo Tutoria dentro de Tutor",
 *   description="Este Resource se utiliza únicamente dentro del TutorResource. Sin embargo, se puede utilizar para un posible Servicio Equipo Colaborativo",
 *   type="object",
 *   example={
 *      "nombre": "Equipo Colaborativo ICO",
 *      "licenciatura": "ICO"
 *   }
 * )
 *
 */

class TutoriaEquipoResource extends JsonResource
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
   *     property="licenciatura",
   *     type="string"
   * )
   *
   */

  public function toArray(Request $request): array
  {
    return [
      'nombre' => $this->nombre,
      'licenciatura' => $this->licenciatura->clave
    ];
  }
}
