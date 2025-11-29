<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocenteRequest;
use App\Http\Requests\UpdateDocenteRequest;
use App\Http\Resources\DocenteResource;
use App\Models\Docente;
use Illuminate\Http\JsonResponse;

class DocenteController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Docentes"},
     *   path="/v1/docentes",
     *   summary="Obtener a todos los Docentes",
     *   description="Se obtienen a todos los docentes registrados.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Respuesta Exitosa",
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
     *         @OA\Items(ref="#/components/schemas/DocenteResource")
     *       )
     *     )
     *   )
     * )
     */
    public function getDocentes(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => DocenteResource::collection(Docente::all())
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Docentes"},
     *   path="/v1/docentes/rfc/{rfc}",
     *   summary="Obtener al Docente por RFC",
     *   description="Se obtiene al Docente proporcionando su RFC.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="rfc",
     *     in="path",
     *     description="RFC del Docente",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="GAP850312C48A"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Respuesta Exitosa",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=200
     *       ),
     *       @OA\Property(
     *         property="data",
     *         ref="#/components/schemas/DocenteResource"
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
    public function getDocente(string $rfc): JsonResponse
    {
        $docente = Docente::firstWhere('rfc', $rfc);

        abort_if(!$docente, 404, 'Docente no encontrado');

        return response()->json([
            'code' => 200,
            'data' => new DocenteResource(Docente::firstWhere('rfc', $rfc))
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Docentes"},
     *   path="/v1/docentes/rfc/{rfc}/exists",
     *   summary="Verificar la existencia del Docente por RFC",
     *   description="Se verifica la existencia del Docente. Se regresa un valor booleano y el ID del Docente. Si no existe el Docente se regresa un false y se regresa una cadena vacía como ID del Docente",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="rfc",
     *     in="path",
     *     description="RFC del Docente",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="GAP850312C48A"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Respuesta Exitosa",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=200
     *       ),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(
     *           property="exists",
     *           type="boolean",
     *           example="true"
     *         ),
     *         @OA\Property(
     *           property="id",
     *           type="string",
     *           example="9cc8a487-63c4-4d2d-ab11-26951a3b846e"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function getDocenteCheckExists(string $rfc): JsonResponse
    {
        $docente = Docente::firstWhere('rfc', $rfc);

        return response()->json([
            'code' => 200,
            'data' => [
                'exists' => ($docente) ? true : false,
                'id' => ($docente) ? $docente->id : '',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *   tags={"Docentes"},
     *   path="/v1/docentes",
     *   summary="Crear un nuevo Docente",
     *   description="Se crea un nuevo Docente, se debe considerar que es validado el RequestBody, por lo que, se debe poner especial atención en el contenido y formato del RequestBody que es enviado para crear al nuevo Docente. No se consideran los campos adicionales.",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StoreDocenteRequest")
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Creación Exitosa",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=201
     *       ),
     *       @OA\Property(
     *         property="data",
     *         ref="#/components/schemas/DocenteResource"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Bad Request",
     *     @OA\JsonContent(ref="#/components/schemas/ResponseBadRequest")
     *   )
     * )
     */
    public function postDocente(StoreDocenteRequest $docente): JsonResponse
    {
        $docenteValidado = $docente->safe()->all();

        return response()->json([
            'code' => 201,
            'data' => new DocenteResource(Docente::create($docenteValidado)->refresh())
        ], 201);
    }

    /**
     * @OA\Put(
     *   tags={"Docentes"},
     *   path="/v1/docentes/rfc/{rfc}",
     *   summary="Actualizar un Docente existente",
     *   description="Se actualiza un Docente existente, se debe considerar que es validado el RequestBody, por lo que, se debe poner especial atención en el contenido y formato del RequestBody que es enviado para actualizar el Docente existente. Además, se realiza la actualización de todos los campos validos que son enviados en el RequestBody. Asimismo, se debe tener en cuenta que no son necesarios todos los campos para actualizar al Docente. No se consideran los campos adicionales.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="rfc",
     *     in="path",
     *     description="RFC del Docente",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="GAP850312C48A"
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateDocenteRequest")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Actualización Exitosa",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=200
     *       ),
     *       @OA\Property(
     *         property="data",
     *         ref="#/components/schemas/DocenteResource"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Bad Request",
     *     @OA\JsonContent(ref="#/components/schemas/ResponseBadRequest")
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(ref="#/components/schemas/ResponseError")
     *   )
     * )
     */
    public function putDocente(string $rfc, UpdateDocenteRequest $docenteUpdateData): JsonResponse
    {
        $docenteUpdateDataValidado = $docenteUpdateData->safe()->all();

        $docente = docente::firstWhere('rfc', $rfc);

        abort_if(!$docente, 404, 'Docente no encontrado');

        $docente->update($docenteUpdateDataValidado);

        return response()->json([
            'code' => 200,
            'data' => new DocenteResource($docente)
        ]);
    }
}
