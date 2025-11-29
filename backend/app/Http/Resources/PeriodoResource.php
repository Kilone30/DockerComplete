<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  schema="PeriodoResource",
 *  title="Resource Periodo",
 *  type="object",
 *  example= {
 *    "periodo": "2022A",
 *    "fecha_inicio": "2022-02-01",
 *    "fecha_final": "2022-07-15"
 *   },
 *   @OA\Property(
 *     property="periodo",
 *     type="string"
 *   ),
 *   @OA\Property(
 *     property="fecha_inicio",
 *     type="date"
 *   ),
 *   @OA\Property(
 *     property="fecha_final",
 *     type="date"
 *   )
 *  )
 */

class PeriodoResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */

  public function toArray(Request $request): array
  {
    return [
      'periodo' => $this->periodo,
      'fecha_inicio' => $this->fecha_inicio,
      'fecha_final' => $this->fecha_final
    ];
  }
}
