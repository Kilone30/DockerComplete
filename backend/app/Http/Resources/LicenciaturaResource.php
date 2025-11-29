<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="LicenciaturaResource",
 *   title="Resource Licenciatura",
 *   type="object",
 *   example={
 *    "clave": "ICO",
 *    "nombre": "Ingeniería en computación"
 *   },
 *   @OA\Property(
 *    property="clave",
 *    type="string"
 *   ),
 *   @OA\Property(
 *    property="nombre",
 *    type="string"
 *   )
 * )
 */
class LicenciaturaResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'clave' => $this->clave,
      'nombre' => $this->nombre,
    ];
  }
}
