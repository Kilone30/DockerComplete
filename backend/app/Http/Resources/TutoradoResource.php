<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *   schema="TutoradoResource",
 *   title="Resource Tutorado",
 *   type="object",
 *   example={
 *      "num_cuenta": "2245123",
 *      "nombre": "Salvador",
 *      "primer_apellido": "Benitez",
 *      "segundo_apellido": "Sanchez",
 *      "genero": "M",
 *      "correo_personal": "salvador123@gmail.com",
 *      "correo_institucional": "sbenitezs002@alumno.uaemex.mx",
 *      "licenciaturas": {
 *          "clave": "ICO",
 *          "nombre": "Ingeniería en Computación"
 *      }
 *   }
 * )
 *
 */

class TutoradoResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */

  /**
   *
   * @OA\Property(
   *    property="num_cuenta",
   *    type="string"
   * )
   *
   *
   * @OA\Property(
   *    property="nombre",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *    property="primer_apellido",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *    property="segundo_apellido",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *    property="genero",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *    property="correo_personal",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *    property="correo_institucional",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *    property="licenciatura",
   *    type="object",
   *    @OA\Property(
   *        type="string",
   *        property="clave"
   *    ),
   *    @OA\Property(
   *        type="string",
   *        property="nombre"
   *    )
   * )
   *
   */

  public function toArray(Request $request): array
  {
    return [
      'num_cuenta' => $this->num_cuenta,
      'nombre' => $this->nombre,
      'primer_apellido' => $this->primer_apellido,
      'segundo_apellido' => $this->segundo_apellido,
      'genero' => $this->genero,
      'correo_personal' => $this->correo_personal,
      'correo_institucional' => $this->correo_institucional,
      'licenciatura' => [
        'clave' => $this->licenciatura->clave,
        'nombre' => $this->licenciatura->nombre
      ]
    ];
  }
}
