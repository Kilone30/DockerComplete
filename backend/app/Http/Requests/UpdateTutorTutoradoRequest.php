<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *   schema="UpdateTutorTutoradoRequest",
 *   title="Request Update Tutor Tutorado",
 *   type="object",
 *   example={
 *      "num_cuenta": "2245123",
 *      "rfc_destino": "LTZE02120943Q"
 *   },
 *   @OA\Property(
 *     property="num_cuenta",
 *     type="string",
 *     example="required|string|size:7|exists:alumnos,num_cuenta"
 *   ),
 *   @OA\Property(
 *     property="rfc_destino",
 *     type="string",
 *     example="required|string|size:13|exists:docentes,rfc"
 *   )
 * )
 */

class UpdateTutorTutoradoRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return Auth::check() && Auth::user()->can('update');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */

  public function rules(): array
  {
    return [
      'num_cuenta' => 'required|string|size:7|exists:alumnos,num_cuenta',
      'rfc_destino' => 'required|string|exists:docentes,rfc'
    ];
  }

  public function messages(): array
  {
    return [
      'num_cuenta.required' => 'Se debe dar un número de cuenta',
      'num_cuenta.string' => 'El número de cuenta debe ser una cadena',
      'num_cuenta.size' => 'El número de cuenta debe ser de exactamente 7 carácteres',
      'rfc_destino.required' => 'Se debe dar un rfc del tutor destino',
      'rfc_destino.string' => 'El rfc del tutor destino debe ser una cadena'
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
