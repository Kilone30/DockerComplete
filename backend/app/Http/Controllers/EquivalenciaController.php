<?php

namespace App\Http\Controllers;

use App\Models\Equivalencia;
use App\Models\Materia;
use App\Http\Resources\EquivalenciaResource;

class EquivalenciaController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Equivalencias"},
     *   path="/v1/equivalencias/{clave}",
     *   summary="Obtener todas las materias equivalentes a una dada",
     *   @OA\Parameter(
     *     name="clave",
     *     in="path",
     *     description="Clave de la materia",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="IIAT01"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Respuesta exitosa",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=200
     *       ),
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/EquivalenciaResource")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(ref="#/components/schemas/ResponseError")
     *   )
     * )
     */
    public function getEquivalencias(string $clave)
    {
        $materia = Materia::firstWhere('clave', $clave);

        abort_if(!$materia, 404, 'Materia no encontrada');

        return response()->json([

            'code' => 200,
            'data' => EquivalenciaResource::collection(Equivalencia::where('materia_id', '=', $materia->id)->get())
        ]);
    }
}
