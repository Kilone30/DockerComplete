<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 
 * @OA\Schema(
 *   schema="EquivalenciaResource",
 *   title="Resource Equivalencia",
 *   type="object",
 *   example={
 *    "equivalencia": "IIAT01"
 *   },
 *   @OA\Property(
 *    property="equivalencia",
 *    type="string"
 *   )
 * )
 */
class EquivalenciaResource extends JsonResource
{
  /** 
   * 
   * Transform the resource into an array.
   * 
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'equivalencia' => $this->equivalente_id->clave
    ];
  }
}
