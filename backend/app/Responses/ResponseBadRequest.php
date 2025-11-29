<?php

namespace App\Responses;

/**
 *
 * @OA\Schema(
 *   schema="ResponseBadRequest",
 *   title="Response Bad Request",
 *   type="object",
 *   @OA\Property(
 *      property="code",
 *      type="integer",
 *      example=400
 *   ),
 *   @OA\Property(
 *      property="message",
 *      type="string",
 *      example="Existen errores de validación del RequestBody enviado"
 *   ),
 *   @OA\Property(
 *      property="errors",
 *      type="object",
 *      @OA\Property(
 *        property="propiedad_1",
 *        type="array",
 *        @OA\Items(
 *          type="string",
 *          example="La propiedad ya se encuentra registrada"
 *        )
 *      ),
 *      @OA\Property(
 *        property="propiedad_2",
 *        type="array",
 *        @OA\Items(
 *          type="string",
 *          example="La propiedad debe ser de exactamente n carácteres"
 *        )
 *      ),
 *      @OA\Property(
 *        property="propiedad_3",
 *        type="array",
 *        @OA\Items(
 *          type="string",
 *          example="La propiedad no está registrada"
 *        )
 *      )
 *   )
 * )
 *
 */

class ResponseBadRequest {}
