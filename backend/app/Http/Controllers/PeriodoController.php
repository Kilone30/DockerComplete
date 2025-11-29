<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodoRequest;
use App\Http\Requests\UpdatePeriodoRequest;
use App\Http\Resources\PeriodoResource;
use App\Models\Periodo;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PeriodoController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Periodos"},
     *   path="/v1/periodos",
     *   summary="Obtener a todas los Periodos",
     *   description="Se obtienen todos los Periodos registrados.",
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
     *         @OA\Items(ref="#/components/schemas/PeriodoResource")
     *       )
     *     )
     *   )
     * )
     */
    public function getPeriodos(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => PeriodoResource::collection(Periodo::all())
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Periodos"},
     *   path="/v1/periodos/actual",
     *   summary="Obtener el periodo actual",
     *   description="Se obtiene el periodo actual",
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
     *         @OA\Items(ref="#/components/schemas/PeriodoResource")
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
    public function getPeriodoActual(): JsonResponse
    {
        $today = Carbon::today();
        $periodo = Periodo::whereDate('fecha_inicio', '<=', $today)->whereDate('fecha_final', '>=', $today)->first();

        abort_if(!$periodo, 404, "Periodo no existente");

        return response()->json([
            'code' => 200,
            'data' => new PeriodoResource($periodo)
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Periodos"},
     *   path="/v1/periodos/periodo/{periodo}",
     *   summary="Obtener el Periodo por abreviación",
     *   description="Se obtiene el Periodo por la abreviatura que representa a este (2022A, 2022B, 2024A).",
     *   @OA\Parameter(
     *     name="periodo",
     *     in="path",
     *     description="Abreviación del Periodo",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="2022A"
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
     *         ref="#/components/schemas/PeriodoResource"
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
    public function getPeriodo(string $periodo): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => new PeriodoResource(Periodo::firstWhere('periodo', $periodo))
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Periodos"},
     *   path="/v1/periodos/periodo/{periodo}/exists",
     *   summary="Verificar la existencia del Periodo por Alias",
     *   description="Se verifica la existencia del Periodo. Se regresa un valor booleano y el ID del Periodo. Si no existe el Periodo se regresa un false y se regresa un -1 como ID del Periodo",
     *   @OA\Parameter(
     *     name="periodo",
     *     in="path",
     *     description="Abreviación del Periodo",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="2022A"
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
     *           type="integer",
     *           example=1
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function getPeriodoCheckExists(string $periodoA): JsonResponse
    {
        $periodo = Periodo::firstWhere('periodo', $periodoA);

        return response()->json([
            'code' => 200,
            'data' => [
                'exists' => ($periodo) ? true : false,
                'id' => ($periodo) ? $periodo->id : -1,
            ]
        ]);
    }

    /**
     * @OA\Post(
     *   tags={"Periodos"},
     *   path="/v1/periodos",
     *   summary="Crear un nuevo Periodo",
     *   description="Se crea un nuevo Periodo, se debe considerar que es validado el RequestBody, por lo que, se debe poner especial atención en el contenido y formato del RequestBody que es enviado para crear el nuevo Periodo. No se consideran los campos adicionales.",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StorePeriodoRequest")
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
     *         ref="#/components/schemas/PeriodoResource"
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
    public function postPeriodo(StorePeriodoRequest $periodo): JsonResponse
    {
        $periodoValidado = $periodo->safe()->all();

        return response()->json([
            'code' => 201,
            'data' => new PeriodoResource(Periodo::create($periodoValidado))
        ], 201);
    }

    /**
     * @OA\Put(
     *   tags={"Periodos"},
     *   path="/v1/periodos/periodo/{periodo}",
     *   summary="Actualizar un Periodo existente",
     *   description="Se actualiza un Periodo existente, se debe considerar que es validado el RequestBody, por lo que, se debe poner especial atención en el contenido y formato del RequestBody que es enviado para actualizar el Periodo existente. Además, se realiza la actualización de todos los campos validos que son enviados en el RequestBody. Asimismo, se debe tener en cuenta que no son necesarios todos los campos para actualizar el Periodo. No se consideran los campos adicionales.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="periodo",
     *     in="path",
     *     description="Abreviación del Periodo",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="2022A"
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdatePeriodoRequest")
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
     *         ref="#/components/schemas/PeriodoResource"
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
    public function putPeriodo(string $periodoAv, UpdatePeriodoRequest $periodoUpdateData): JsonResponse
    {
        $periodoUpdateDataValidado = $periodoUpdateData->safe()->all();

        $periodo = periodo::firstWhere('periodo', $periodoAv);

        abort_if(!$periodo, 404, 'Periodo no encontrado');

        $periodo->update($periodoUpdateDataValidado);

        return response()->json([
            'code' => 200,
            'data' => new PeriodoResource($periodo)
        ]);
    }
}
