<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *   schema="AlumnoResource",
 *   title="Resource Alumno",
 *   description="La propiedad 'tutor' puede tener 'null' si el alumno no tiene tutor asociado o NO existir si el alumno es Nuevo, la propiedad 'periodo_ingreso' puede tener 'null' si no se tiene registrada la información",
 *   type="object",
 *   example={
 *      "num_cuenta": "2245123",
 *      "nombre": "Salvador",
 *      "primer_apellido": "Benitez",
 *      "segundo_apellido": "Sanchez",
 *      "genero": "M",
 *      "correo_personal": "salvador123@gmail.com",
 *      "correo_institucional": "sbenitezs002@alumno.uaemex.mx",
 *      "tutor": {
 *          "nombre": "Ana",
 *          "primer_apellido": "Garcia",
 *          "segundo_apellido": "Perez",
 *          "correo_institucional": "agarciap005@profesor.uaemex.mx"
 *      },
 *      "licenciaturas": {
 *          "clave": "ICO",
 *          "nombre": "Ingeniería en Computación"
 *      },
 *      "plan_estudios": "F3",
 *      "linea_acentuacion": "Desarrollo de Software de Aplicación",
 *      "periodo_ingreso": "2024A"
 *   }
 * )
 *
 */

class AlumnoResource extends JsonResource
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
   * ),
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
   *    property="tutor",
   *    type="object",
   *    @OA\Property(
   *        property="nombre",
   *        type="string"
   *    ),
   *    @OA\Property(
   *        property="primer_apellido",
   *        type="string"
   *    ),
   *    @OA\Property(
   *        property="segundo_apellido",
   *        type="string"
   *    ),
   *    @OA\Property(
   *        property="correo_institucional",
   *        type="string"
   *    )
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
   * @OA\Property(
   *    property="plan_estudios",
   *    type="string"
   * ),
   *
   *
   * @OA\Property(
   *    property="linea_acentuacion",
   *    type="string"
   * ),
   * 
   * @OA\Property(
   *   property="periodo_ingreso",
   *   type="string"
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
      'tutor' => new AlumnoTutorResource($this->whenLoaded('docente')),
      'licenciatura' => [
        'clave' => $this->licenciatura->clave,
        'nombre' => $this->licenciatura->nombre
      ]
    ];
  }
}
