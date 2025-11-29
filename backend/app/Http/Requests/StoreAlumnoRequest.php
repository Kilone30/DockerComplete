<?php

namespace App\Http\Requests;

use App\Models\Alumno;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 *
 * @OA\Schema(
 *   schema="StoreAlumnoRequest",
 *   title="Request Store Alumno",
 *   type="object",
 *   example={
 *      "num_cuenta": "1841456",
 *      "nombre": "Alumno",
 *      "primer_apellido": "Prueba",
 *      "segundo_apellido": "Creacion",
 *      "genero": "M",
 *      "correo_personal": "alumno@gmail.com",
 *      "correo_institucional": "alumno@alumno.uaemex.mx",
 *      "licenciatura": "ICO",
 *      "plan_estudio": "F3",
 *      "linea_acentuacion": "Desarrollo de Software de Aplicación"
 *   }
 * )
 *
 */

class StoreAlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('create', Alumno::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    /**
     *
     * @OA\Property(
     *    property="num_cuenta",
     *    type="string",
     *    example="required|string|size:7|unique:alumnos"
     * ),
     *
     *
     * @OA\Property(
     *    property="nombre",
     *    type="string",
     *    example="required|string"
     * ),
     *
     *
     * @OA\Property(
     *    property="primer_apellido",
     *    type="string",
     *    example="required|string"
     * ),
     *
     *
     * @OA\Property(
     *    property="segundo_apellido",
     *    type="string",
     *    example="string"
     * ),
     *
     *
     * @OA\Property(
     *    property="genero",
     *    type="string",
     *    example="['required', Rule::in(['M', 'F'])]"
     * ),
     *
     *
     * @OA\Property(
     *    property="correo_personal",
     *    type="string",
     *    example="string|email|unique:alumnos"
     * ),
     *
     *
     * @OA\Property(
     *    property="correo_institucional",
     *    type="string",
     *    example="string|email|unique:alumnos"
     * )
     *
     *
     * @OA\Property(
     *    property="licenciatura",
     *    type="string",
     *    example="required|exists:licenciaturas,clave"
     * )
     *
     * @OA\Property(
     *    property="plan_estudio",
     *    type="string",
     *    example="required|exists:plan_estudios,nombre"
     * )
     *
     * @OA\Property(
     *    property="linea_acentuacion",
     *    type="string",
     *    example="exists:linea_especializadas,nombre"
     * )
     *
     */

    public function rules(): array
    {
        return [
            'num_cuenta' => 'required|string|size:7|unique:alumnos',
            'nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'segundo_apellido' => 'string',
            'genero' => ['required', Rule::in(['M', 'F'])],
            'correo_personal' => 'string|email|unique:alumnos',
            'correo_institucional' => 'string|email|unique:alumnos',
            'licenciatura' => 'required|exists:licenciaturas,clave',
            'plan_estudio' => 'required|exists:plan_estudios,nombre',
            'linea_acentuacion' => 'exists:linea_especializadas,nombre'
        ];
    }

    public function messages(): array
    {
        return [
            'num_cuenta.required' => 'Se debe dar un número de cuenta',
            'num_cuenta.string' => 'El número de cuenta debe ser una cadena',
            'num_cuenta.size' => 'El número de cuenta debe ser de exactamente 7 carácteres',
            'num_cuenta.unique' => 'El número de cuenta ya se encuentra registrado',
            'nombre.required' => 'Se debe dar un nombre',
            'nombre.string' => 'El nombre debe ser una cadena',
            'primer_apellido.required' => 'Se debe dar un primer apellido',
            'primer_apellido.string' => 'El primer apellido debe ser una cadena',
            'segundo_apellido.string' => 'El segundo apellido debe ser una cadena',
            'genero.required' => 'Se debe dar un género',
            'genero.in' => 'Las opciones de género son "M" y "F"',
            'correo_personal.string' => 'El correo personal debe ser una cadena',
            'correo_personal.email' => 'El correo personal no tiene un formato de correo valido',
            'correo_personal.unique' => 'El correo personal ya se encuentra registrado',
            'correo_institucional.string' => 'El correo institucional debe ser una cadena',
            'correo_institucional.email' => 'El correo institucional no tiene un formato de correo valido',
            'correo_institucional.unique' => 'El correo institucional ya se encuentra registrado',
            'licenciatura.required' => 'Se debe dar una clave de licenciatura',
            'licenciatura.exists' => 'La clave de licenciatura no está registrada, las opciones de clave de licenciatura son ICO, IEL, IME, ICI y ISES',
            'plan_estudio.required' => 'Se debe dar un plan de estudios',
            'plan_estudio.exists' => 'El plan de estudio no está registrado, ejemplo: F3, F19, etc.',
            'linea_acentuacion.exists' => 'La línea de acentuación no está registrada, ejemplo: Desarrollo de Software de Aplicación'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 400,
            'message' => 'Existen errores de validación del RequestBody enviado',
            'errors' => $validator->errors()
        ], 400));
    }
}
