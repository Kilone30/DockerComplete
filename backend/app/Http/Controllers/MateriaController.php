<?php

namespace App\Http\Controllers;

use App\Http\Resources\MateriaResource;
use App\Models\Materia;

class MateriaController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Materias"},
     *   path="/v1/materias",
     *   summary="Materia index",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="code",
     *         type="integer",
     *         example="200",
     *       ),
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/MateriaResource")
     *       )
     *     )
     *   )
     * )
     */
    public function getAllMaterias()
    {
        return response()->json([
            'code' => 200,
            'data' => MateriaResource::collection(Materia::all())
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Materias"},
     *   path="/v1/materias/{clave}",
     *   summary="Materia show",
     *   @OA\Parameter(
     *     name="clave",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     example="L41601",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="code",
     *         type="integer",
     *         example="200",
     *       ),
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/MateriaResource")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getMateria(string $clave)
    {
        $materia = Materia::firstWhere('clave', $clave);

        abort_if(!$materia, 404, 'Materia no encontrada');

        return response()->json([
            'code' => 200,
            'data' => new MateriaResource($materia)
        ]);
    }
}
