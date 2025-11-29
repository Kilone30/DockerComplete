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
 *   schema="UpdateAlumnoRequest",
 *   title="Request Update Alumno",
 *   type="object",
 *   example={
 *      "nombre": "Salvador",
 *      "primer_apellido": "Benitez",
 *      "segundo_apellido": "Sanchez",
 *      "genero": "M",
 *      "correo_personal": "salvador123@gmail.com",
 *      "correo_institucional": "sbenitezs002@alumno.uaemex.mx"
 *   }
 * )
 *
 */

class UpdateAlumnoRequest extends FormRequest
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
     *    property="nombre",
     *    type="string",
     *    example="string"
     * ),
     *
     *
     * @OA\Property(
     *    property="primer_apellido",
     *    type="string",
     *    example="string"
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
     *    example="[Rule::in(['M', 'F'])]"
     * ),
     *
     *
     * @OA\Property(
     *    property="correo_personal",
     *    type="string",
     *    example="['string', 'email', Rule::unique('alumnos')->ignore($num_cuenta, 'num_cuenta')]"
     * ),
     *
     *
     * @OA\Property(
     *    property="correo_institucional",
     *    type="string",
     *    example="['string', 'email', Rule::unique('alumnos')->ignore($num_cuenta, 'num_cuenta')]"
     * )
     *
     */

    public function rules(): array
    {
        $num_cuenta = request()->route('num_cuenta');

        return [
            'nombre' => 'string',
            'primer_apellido' => 'string',
            'segundo_apellido' => 'string',
            'genero' => [Rule::in(['M', 'F'])],
            'correo_personal' => ['string', 'email', Rule::unique('alumnos')->ignore($num_cuenta, 'num_cuenta')],
            'correo_institucional' => ['string', 'email', Rule::unique('alumnos')->ignore($num_cuenta, 'num_cuenta')]
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.string' => 'El nombre debe ser una cadena',
            'primer_apellido.string' => 'El primer apellido debe ser una cadena',
            'segundo_apellido.string' => 'El segundo apellido debe ser una cadena',
            'genero.in' => 'Las opciones de género son "M" y "F"',
            'correo_personal.string' => 'El correo personal debe ser una cadena',
            'correo_personal.email' => 'El correo personal no tiene un formato de correo valido',
            'correo_personal.unique' => 'El correo personal ya se encuentra registrado',
            'correo_institucional.string' => 'El correo institucional debe ser una cadena',
            'correo_institucional.email' => 'El correo institucional no tiene un formato de correo valido',
            'correo_institucional.unique' => 'El correo institucional ya se encuentra registrado'
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
