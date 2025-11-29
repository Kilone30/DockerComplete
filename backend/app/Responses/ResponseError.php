<?php

namespace App\Responses;

/**
 *
 * @OA\Schema(
 *   schema="ResponseError",
 *   title="Response Error",
 *   type="object",
 *   example={
 *      "code": 404,
 *      "message": "Recurso no encontrado"
 *   },
 *   @OA\Property(
 *      property="code",
 *      type="integer"
 *   ),
 *   @OA\Property(
 *      property="message",
 *      type="string"
 *   ),
 * )
 *
 */

class ResponseError {}
