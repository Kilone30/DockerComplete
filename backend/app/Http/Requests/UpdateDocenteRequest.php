<?php

namespace App\Http\Requests;

use App\Models\Docente;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 *
 * @OA\Schema(
 *   schema="UpdateDocenteRequest",
 *   title="Request Update Docente",
 *   type="object",
 *   example={
 *      "rfc": "XXXX950708X00",
 *      "nombre": "Ana",
 *      "primer_apellido": "García",
 *      "segundo_apellido": "Pérez",
 *      "correo_institucional": "agarciap005@profesor.uaemex.mx",
 *      "curp": "GAPX850312MDFRRNA3",
 *      "genero": "F"
 *   }
 * )
 *
 */

class UpdateDocenteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('update', Docente::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    /**
     * 
     * @OA\Property(
     *    property="rfc",
     *    type="string"
     * ),
     *
     * @OA\Property(
     *    property="nombre",
     *    type="string"
     * ),
     *
     *
     * @OA\Property(
     *    property="primer_apellido",
     *    type="string"
     * ),
     *
     *
     * @OA\Property(
     *    property="segundo_apellido",
     *    type="string"
     * ),
     *
     *
     * @OA\Property(
     *    property="correo_institucional",
     *    type="string"
     * ),
     *
     *
     * @OA\Property(
     *    property="curp",
     *    type="string"
     * )
     *
     * @OA\Property(
     *    property="genero",
     *    type="string"
     * )
     *
     */

    public function rules(): array
    {
        $rfc = request()->route('rfc');

        return [
            'rfc' => 'string|unique:docentes',
            'nombre' => 'string',
            'primer_apellido' => 'string',
            'segundo_apellido' => 'string',
            'correo_institucional' => ['string', 'email', Rule::unique('docentes')->ignore($rfc, 'rfc')],
            'curp' => ['string', 'size:18', Rule::unique('docentes')->ignore($rfc, 'rfc')],
            'genero' => [Rule::in(['M', 'F'])]
        ];
    }

    public function messages(): array
    {
        return [
            'rfc.string' => 'El rfc debe ser una cadena',
            'rfc.unique' => 'El rfc ya se encuentra registrado',
            'nombre.string' => 'El nombre debe ser una cadena',
            'primer_apellido.string' => 'El primer apellido debe ser una cadena',
            'segundo_apellido.string' => 'El segundo apellido debe ser una cadena',
            'correo_institucional.string' => 'El correo institucional debe ser una cadena',
            'correo_institucional.email' => 'El correo institucional no tiene un formato de correo valido',
            'correo_institucional.unique' => 'El correo institucional ya se encuentra registrado',
            'curp.string' => 'La curp debe ser una cadena',
            'curp.size' => 'La curp debe ser de exactamente 18 carácteres',
            'curp.unique' => 'La curp ya se encuentra registrado',
            'genero.in' => 'Las opciones de género son "M" y "F"'
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
