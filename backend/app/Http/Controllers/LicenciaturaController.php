<?php

namespace App\Http\Controllers;

use App\Http\Resources\LicenciaturaResource;
use App\Models\Licenciatura;

/**
 * @OA\Get(
 *   tags={"Licenciaturas"},
 *   path="/v1/licenciaturas",
 *   summary="Obtener todas las licenciaturas",
 *   description="Se obtienen todas las licenciaturas.",
 *   @OA\Response(
 *      response=200, 
 *      description="Respuesta exitosa",
 *      @OA\JsonContent(
 *        type="object",
 *        @OA\Property(
 *          property="code",
 *          type="integer",
 *          example=200
 *        ),
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/LicenciaturaResource")
 *        )
 *      )
 *   ),
 * )
 */
class LicenciaturaController extends Controller
{
    public function getLicenciaturas()
    {
        return response()->json([
            'code' => 200,
            'data' => LicenciaturaResource::collection(Licenciatura::all()),
        ]);
    }
}
