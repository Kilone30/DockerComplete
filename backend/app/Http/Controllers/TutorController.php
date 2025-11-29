<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTutorTutoradoRequest;
use App\Http\Requests\UpdateTutorEquipoRequest;
use App\Http\Resources\AlumnoResource;
use App\Http\Resources\TutoradoResource;
use App\Http\Resources\TutorResource;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\TutoriaEquipo;
use Symfony\Component\HttpFoundation\JsonResponse;

class TutorController extends Controller
{
  /**
   * @OA\Get(
   *   tags={"Tutores"},
   *   path="/v1/tutores",
   *   summary="Obtener a todos los Tutores",
   *   description="Se obtienen todos los Tutores registrados.",
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
   *         @OA\Items(ref="#/components/schemas/TutorResource")
   *       )
   *     )
   *   )
   * )
   */
  public function getTutores(): JsonResponse
  {
    return response()->json([
      'code' => 200,
      'data' => TutorResource::collection(Docente::with('tutoria_equipos')->has('tutoria_equipos')->get())
    ]);
  }

  /**
   * @OA\Get(
   *   tags={"Tutores"},
   *   path="/v1/tutores/tutores-individuales",
   *   summary="Obtener a todos los Tutores Individuales",
   *   description="Se obtienen todos los Tutores que están en modalidad de Tutor Individual.",
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
   *         @OA\Items(ref="#/components/schemas/TutorResource")
   *       )
   *     )
   *   )
   * )
   */
  public function getTutoresIndividuales(): JsonResponse
  {
    return response()->json([
      'code' => 200,
      'data' => TutorResource::collection(
        Docente::with('tutoria_equipos')
          ->where('tutor', true)
          ->whereHas('tutoria_equipos', function ($query) {
            $query->where('colaborativo', false);
          })
          ->get()
      )
    ]);
  }

  /**
   * @OA\Get(
   *   tags={"Tutores"},
   *   path="/v1/tutores/tutores-equipo",
   *   summary="Obtener a todos los Tutores Equipo Colaborativo",
   *   description="Se obtienen todos los Tutores que están en modalidad de Tutor en Equipo Colaborativo.",
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
   *         @OA\Items(ref="#/components/schemas/TutorResource")
   *       )
   *     )
   *   )
   * )
   */
  public function getTutoresEquipos(): JsonResponse
  {
    return response()->json([
      'code' => 200,
      'data' => TutorResource::collection(
        Docente::with('tutoria_equipos')
          ->where('tutor', true)
          ->whereHas('tutoria_equipos', function ($query) {
            $query->where('colaborativo', true);
          })
          ->get()
      )
    ]);
  }

  /**
   * @OA\Get(
   *   tags={"Tutores"},
   *   path="/v1/tutores/rfc/{rfc}",
   *   summary="Obtener al Tutor por RFC",
   *   description="Se obtiene al Docente proporcionando su RFC.",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="rfc",
   *     in="path",
   *     description="RFC del Tutor",
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
   *         ref="#/components/schemas/TutorResource"
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
  public function getTutor(string $rfc): JsonResponse
  {
    $tutor = Docente::with('tutoria_equipos')->firstWhere('rfc', $rfc);

    abort_if(!$tutor, 404, 'Docente no encontrado');
    abort_if(!$tutor->tutor, 404, 'Tutor no encontrado');

    return response()->json([
      'code' => 200,
      'data' => new TutorResource($tutor)
    ]);
  }

  /**
   * @OA\Get(
   *   tags={"Tutores"},
   *   path="/v1/tutores/rfc/{rfc}/tutorados",
   *   summary="Obtener a todos los Tutorados de un Tutor por RFC",
   *   description="Se obtienen a todos los Tutorados de un Tutor, porporcionando el RFC del Tutor (para su identificación).",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="rfc",
   *     in="path",
   *     description="RFC del Tutor",
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
   *         type="array",
   *         @OA\Items(ref="#/components/schemas/TutoradoResource")
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
  public function getTutorados(string $rfc): JsonResponse
  {
    $tutor = Docente::firstWhere('rfc', $rfc);

    abort_if(!$tutor, 404, 'Docente no encontrado');

    return response()->json([
      'code' => 200,
      'data' => TutoradoResource::collection($tutor->alumnos)
    ]);
  }

  /**
   * @OA\Get(
   *   tags={"Tutores"},
   *   path="/v1/tutores/licenciatura/{licenciatura}",
   *   summary="Obtener a todos los Tutores por Licenciatura",
   *   description="Se obtiene a todos los Tutores de un Equipo Colaborativo de una Licenciatura (Cada Equipo Colaborativo está asignado a una única Licenciatura).",
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
   *         ref="#/components/schemas/TutorResource"
   *       )
   *     )
   *   )
   * )
   */
  public function getTutoresLicenciatura(string $licenciatura): JsonResponse
  {
    return response()->json([
      'code' => 200,
      'data' => TutorResource::collection(
        Docente::with('tutoria_equipos')
          ->where('tutor', true)
          ->whereHas('tutoria_equipos', function ($query) use ($licenciatura) {
            $query->whereHas('licenciaturas', function ($subquery) use ($licenciatura) {
              $subquery->where('clave', 'like', $licenciatura);
            });
          })
          ->get()
      )
    ]);
  }

  /**
   * @OA\Get(
   *   tags={"Tutores"},
   *   path="/v1/tutores/rfc/{rfc}/total-tutorados",
   *   summary="Obtener el total de Tutorados de un Tutor",
   *   description="Se obtiene el número total de Tutorados que tiene un Tutor, porporcionando el RFC del Tutor (para su identificación).",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="rfc",
   *     in="path",
   *     description="RFC del Tutor",
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
   *           property="total_tutorados",
   *           type="integer",
   *           example="200"
   *         )
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
  public function getNumeroTutorados(string $rfc): JsonResponse
  {
    $tutor = Docente::where('rfc', $rfc)->withCount('alumnos')->first();

    abort_if(!$tutor, 404, 'Docente no encontrado');
    abort_if(!$tutor->tutor, 404, 'Tutor no encontrado');

    return response()->json([
      'code' => 200,
      'data' => [
        'total_tutorados' => $tutor->alumnos_count
      ]
    ]);
  }

  /**
   * @OA\Put(
   *   tags={"Tutores"},
   *   path="/v1/tutores/rfc/{rfc}/tutor-equipo",
   *   summary="Agregar un Tutor a un Equpo Colaborativo",
   *   description="Se agrega un Tutor a un Equipo Colaborativo, actualizando los datos de un Docente existente y proporcionando la clave de la licenciatura del Equipo Colaborativo correspondiente (toma el primer equipo colaborativo de la clave de la licenciatura coincidente).",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="rfc",
   *     in="path",
   *     description="RFC del Tutor",
   *     @OA\Schema(
   *       type="string"
   *     ),
   *     required=true,
   *     example="GAP850312C48A"
   *   ),
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(ref="#/components/schemas/UpdateTutorEquipoRequest")
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
   *         ref="#/components/schemas/TutorResource"
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
  public function putTutorEquipo(string $rfc, UpdateTutorEquipoRequest $licenciaturaEquipo)
  {
    $validated = $licenciaturaEquipo->safe()->all();

    $tutoria_equipo = TutoriaEquipo::whereHas('licenciaturas', function ($query) use ($validated) {
      $query->where('clave', 'like', $validated['licenciatura']);
    })->where('colaborativo', $validated['colaborativo'])->first();

    abort_if(!$tutoria_equipo, 404, 'Equipo de tutoría no encontrado para la licenciatura especificada');

    $tutor = Docente::firstWhere('rfc', $rfc);

    abort_if(!$tutor, 404, 'Docente no encontrado');

    $tutor->tutor = true;

    $tutor->save();

    $tutor->tutoria_equipos()->sync([$tutoria_equipo->id]);

    $tutor = $tutor->fresh(['tutoria_equipos']);

    return response()->json([
      'code' => 200,
      'data' => new TutorResource($tutor)
    ]);
  }

  /**
   * @OA\Put(
   *   tags={"Tutores"},
   *   path="/v1/tutores/rfc/{rfc}/asignar-tutorado",
   *   summary="Asignar un Tutorado a un Tutor",
   *   description="Se agrega un Tutorado a un Tutor, actualizando los datos de un Docente existente que es un Tutor y proporcionando el número de cuenta del Alumno (Tutorado) a tráves del BodyRequest.",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="rfc",
   *     in="path",
   *     description="RFC del Tutor",
   *     @OA\Schema(
   *       type="string"
   *     ),
   *     required=true,
   *     example="GAP850312C48A"
   *   ),
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(ref="#/components/schemas/UpdateTutorTutoradoRequest")
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
   *   ),
   *   @OA\Response(
   *     response=409,
   *     description="Conflicto de Datos",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/ResponseError",
   *       example={
   *         "code": 409,
   *         "message": "Existe un conflicto con los datos"
   *       }
   *     )
   *   )
   * )
   */
  public function putTutorado(string $rfc, UpdateTutorTutoradoRequest $cuentaAlumno)
  {
    $num_cuenta = $cuentaAlumno->safe()->only(['num_cuenta'])['num_cuenta'];

    $tutor = Docente::firstWhere('rfc', $rfc);

    abort_if(!$tutor, 404, 'Docente no encontrado');
    abort_if(!$tutor->tutor, 404, 'Tutor no encontrado');

    $tutorado = Alumno::firstWhere('num_cuenta', $num_cuenta);

    abort_if(!$tutorado, 404, 'Alumno no encontrado');

    abort_if($tutorado->docente && ($tutorado->docente->id != $tutor->id), 409, 'El Alumno ya tiene un Tutor asignado');

    $tutorado->docente()->associate($tutor);

    $tutorado->save();

    return response()->json([
      'code' => 200,
      'data' => new AlumnoResource($tutorado->refresh())
    ]);
  }

  /**
   * @OA\Put(
   *   tags={"Tutores"},
   *   path="/v1/tutores/rfc/{rfc}/cambio-tutor",
   *   summary="Cambiar el Tutor de un Tutorado",
   *   description="Se cambia el Tutor de un Tutorado, eliminando el Tutor actual y asignando un nuevo Tutor al Tutorado. Asimismo, se proporciona el número de cuenta del Alumno (Tutorado) y el rfc del Tutor nuevo (rfc_destino) a tráves del BodyRequest.",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="rfc",
   *     in="path",
   *     description="RFC del Tutor",
   *     @OA\Schema(
   *       type="string"
   *     ),
   *     required=true,
   *     example="GAP850312C48A"
   *   ),
   *   @OA\RequestBody(
   *     required=true,
   *     @OA\JsonContent(ref="#/components/schemas/UpdateTutorTutoradoRequest")
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
   *   ),
   *   @OA\Response(
   *     response=409,
   *     description="Conflicto de Datos",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/ResponseError",
   *       example={
   *         "code": 409,
   *         "message": "Existe un conflicto con los datos"
   *       }
   *     )
   *   )
   * )
   */
  public function putTutorCambio(string $rfc, UpdateTutorTutoradoRequest $tutor_tutorado)
  {
    $rfc_destino = $tutor_tutorado->safe()->only(['rfc_destino']);
    $num_cuenta = $tutor_tutorado->safe()->only(['num_cuenta']);

    $tutorActual = Docente::firstWhere('rfc', $rfc);

    abort_if(!$tutorActual, 404, 'Docente Actual no encontrado');
    abort_if(!$tutorActual->tutor, 404, 'Tutor Actual no encontrado');

    $tutorDestino = Docente::firstWhere('rfc', $rfc_destino);

    $tutorado = Alumno::firstWhere('num_cuenta', $num_cuenta);

    abort_if(!$tutorado->docente, 409, 'El Alumno no tiene un Tutor asignado');
    abort_if($tutorado->docente->id != $tutorActual->id, 409, 'El Tutor Actual dado no es el mismo que el Tutor real del Alumno');

    $tutorado->docente()->dissociate($tutorActual);

    $tutorado->docente()->associate($tutorDestino);

    $tutorado->save();

    return response()->json([
      'code' => 200,
      'data' => new AlumnoResource($tutorado->refresh())
    ]);
  }

  /**
   * @OA\Delete(
   *   tags={"Tutores"},
   *   path="/v1/tutores/rfc/{rfc}",
   *   summary="Eliminar un Tutor",
   *   description="Eliminar un Tutor. La eliminación es lógica, actualizando el valor del campo 'tutor' a FALSE. Se debe considerar que solo se eliminará un Tutor si este dejó de tener Tutorados.",
   *   security={{"bearerAuth":{}}},
   *   @OA\Parameter(
   *     name="rfc",
   *     in="path",
   *     description="RFC del Tutor",
   *     @OA\Schema(
   *       type="string"
   *     ),
   *     required=true,
   *     example="GAP850312C48A"
   *   ),
   *   @OA\Response(
   *     response=200,
   *     description="Eliminación Exitosa",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="code",
   *         type="integer",
   *         example=200
   *       ),
   *       @OA\Property(
   *         property="data",
   *         ref="#/components/schemas/TutorResource"
   *       )
   *     )
   *   ),
   *   @OA\Response(
   *     response=404,
   *     description="Not Found",
   *     @OA\JsonContent(ref="#/components/schemas/ResponseError")
   *   ),
   *   @OA\Response(
   *     response=409,
   *     description="Conflicto de Datos",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/ResponseError",
   *       example={
   *         "code": 409,
   *         "message": "Existe un conflicto con los datos"
   *       }
   *     )
   *   )
   * )
   */
  public function deleteTutor(string $rfc)
  {
    $tutor = Docente::firstWhere('rfc', $rfc);

    abort_if(!$tutor, 404, 'Docente no encontrado');
    abort_if(!$tutor->alumnos->isEmpty(), 409, 'El Tutor no se puede dar de baja porque tiene alumnos tutorados');

    $tutor->tutor = false;

    $tutor->tutoria_equipos()->detach();

    $tutor->save();

    return response()->json([
      'code' => 200,
      'data' => new TutorResource($tutor)
    ]);
  }
}
