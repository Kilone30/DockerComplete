<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlumnoRequest;
use App\Http\Requests\UpdateAlumnoRequest;
use App\Http\Resources\AlumnoResource;
use App\Models\Alumno;
use App\Models\Licenciatura;
use App\Models\LineaEspecializada;
use App\Models\PlanEstudio;
use Illuminate\Http\JsonResponse;

class AlumnoController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos",
     *   summary="Obtener a todos los Alumnos",
     *   description="Se obtienen todos los alumnos de todas las licenciaturas.",
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
     *         @OA\Items(ref="#/components/schemas/AlumnoResource")
     *       )
     *     )
     *   )
     * )
     */
    public function getAlumnos(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => AlumnoResource::collection(Alumno::with('docente')->get())
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos/cuenta/{num_cuenta}",
     *   summary="Obtener Alumno por el Número de Cuenta del Alumno",
     *   description="Se obtiene un Alumno proporcionando su Número de Cuenta.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="num_cuenta",
     *     in="path",
     *     description="Número de Cuenta del Alumno",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="2245123"
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
     *         ref="#/components/schemas/AlumnoResource"
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
    public function getAlumno(string $num_cuenta)
    {
        $alumno = Alumno::with('docente')->firstWhere('num_cuenta', $num_cuenta);

        abort_if(!$alumno, 404, 'Alumno no encontrado');

        return response()->json([
            'code' => 200,
            'data' => new AlumnoResource($alumno)
        ]);
    }


    /**
     * @OA\Get(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos/licenciatura/{licenciatura}",
     *   summary="Obtener a todos los Alumnos por Licenciatura",
     *   description="Se obtienen todos los alumnos de una Licenciatura proporcionando la clave de la Licenciatura.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="licenciatura",
     *     in="path",
     *     description="Clave de la Licenciatura",
     *     @OA\Schema(
     *       type="string",
     *       enum={"ICO", "IEL", "IME", "ICI", "ISES"}
     *     ),
     *     required=true
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
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/AlumnoResource")
     *       )
     *     )
     *   )
     * )
     */
    public function getAlumnosLicenciatura(string $licenciatura): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => AlumnoResource::collection(Alumno::with('docente')->whereRelation('licenciatura', 'clave', 'like', $licenciatura)->get())
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos/genero/{genero}",
     *   summary="Obtener a todos los Alumnos por el Género",
     *   description="Se obtiene a todos los Alumnos proporcionando su Género.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="genero",
     *     in="path",
     *     description="Género de los Alumnos",
     *     @OA\Schema(
     *       type="string",
     *       enum={"M", "F"}
     *     ),
     *     required=true
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
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/AlumnoResource")
     *       )
     *     )
     *   )
     * )
     */
    public function getAlumosGenero(string $genero): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => AlumnoResource::collection(Alumno::with('docente')->where('genero', $genero)->get())
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos/total",
     *   summary="Obtener el total de Alumnos",
     *   description="Se obtiene el número total de Alumnos registrados.",
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
     *         type="object",
     *         @OA\Property(
     *           property="total_alumnos",
     *           type="integer",
     *           example="2000"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function getTotalAlumnos(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => [
                'total_alumnos' => Alumno::all()->count()
            ]
        ]);
    }

    /**
     * @OA\Get(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos/sin-tutor",
     *   summary="Obtener a todos los Alumnos sin Tutor",
     *   description="Se obtienen a todos los alumnos que no tienen un tutor asignado.",
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
     *         @OA\Items(ref="#/components/schemas/AlumnoResource")
     *       )
     *     )
     *   )
     * )
     */
    public function getAlumnosSinTutor(): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'data' => AlumnoResource::collection(Alumno::whereNull('docente_id')->get())
        ]);
    }

    /**
     * @OA\Post(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos",
     *   summary="Crear un nuevo Alumno",
     *   description="Se crea un nuevo Alumno, se debe considerar que es validado el RequestBody, por lo que, se debe poner especial atención en el contenido y formato del RequestBody que es enviado para crear al nuevo Alumno. No se consideran los campos adicionales.",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/StoreAlumnoRequest")
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
     *         ref="#/components/schemas/AlumnoResource"
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
    public function postAlumno(StoreAlumnoRequest $alumno): JsonResponse
    {
        $alumnoValidado = $alumno->safe()->except(['licenciatura']);

        $licenciatura = Licenciatura::firstWhere('clave', $claveLicenciaturaValidada);

        $alumnoNuevo = new Alumno();
        $alumnoNuevo->num_cuenta = $alumnoValidado['num_cuenta'];
        $alumnoNuevo->nombre = $alumnoValidado['nombre'];
        $alumnoNuevo->primer_apellido = $alumnoValidado['primer_apellido'];
        $alumnoNuevo->segundo_apellido = $alumnoValidado['segundo_apellido'];
        $alumnoNuevo->genero = $alumnoValidado['genero'];
        $alumnoNuevo->correo_personal = $alumnoValidado['correo_personal'];
        $alumnoNuevo->correo_institucional = $alumnoValidado['correo_institucional'];
        $alumnoNuevo->licenciatura_id = $licenciatura->id;

        $alumnoNuevo->save();

        return response()->json([
            'code' => 201,
            'data' => new AlumnoResource($alumnoNuevo)
        ], 201);
    }

    /**
     * @OA\Put(
     *   tags={"Alumnos"},
     *   path="/v1/alumnos/cuenta/{num_cuenta}",
     *   summary="Actualizar un Alumno existente",
     *   description="Se actualiza un Alumno existente, se debe considerar que es validado el RequestBody, por lo que, se debe poner especial atención en el contenido y formato del RequestBody que es enviado para actualizar el Alumno existente. Además, se realiza la actualización de todos los campos validos que son enviados en el RequestBody. Asimismo, se debe tener en cuenta que no son necesarios todos los campos para actualizar al Alumno. No se consideran los campos adicionales.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="num_cuenta",
     *     in="path",
     *     description="Número de Cuenta del Alumno",
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     required=true,
     *     example="2245123"
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/UpdateAlumnoRequest")
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
     *         ref="#/components/schemas/AlumnoResource"
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
    public function putAlumno(string $num_cuenta, UpdateAlumnoRequest $alumnoUpdateData): JsonResponse
    {
        $alumnoUpdateDataValidado = $alumnoUpdateData->safe()->all();

        $alumno = Alumno::with('docente')->firstWhere('num_cuenta', $num_cuenta);

        abort_if(!$alumno, 404, 'Alumno no encontrado');

        $alumno->update($alumnoUpdateDataValidado);

        return response()->json([
            'code' => 200,
            'data' => new AlumnoResource($alumno)
        ]);
    }
}
