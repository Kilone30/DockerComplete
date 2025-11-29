<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *   schema="UpdateTutorEquipoRequest",
 *   title="Request Store Tutor Equipo",
 *   type="object",
 *   example={
 *      "licenciatura": "ICO",
 *      "colaborativo": true
 *   },
 *   @OA\Property(
 *     property="licenciatura",
 *     type="string",
 *     example="required|exists:licenciaturas,clave"
 *   ),
 *   @OA\Property(
 *     property="colaborativo",
 *     type="boolean",
 *     example="required|boolean"
 *   )
 * )
 */
class UpdateTutorEquipoRequest extends FormRequest
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
      'licenciatura' => 'required|exists:licenciaturas,clave',
      'colaborativo' => 'required|boolean'
    ];
  }

  public function messages(): array
  {
    return [
      'licenciatura.required' => 'Se debe dar una clave de licenciatura',
      'licenciatura.exists' => 'La clave de licenciatura no está registrada, las opciones de clave de licenciatura son "ICO", "IEL", "IME", "ICI", "ISES" y "IIA"',
      'colaborativo.required' => 'Se debe especificar si el equipo es colaborativo',
      'colaborativo.boolean' => 'El campo colaborativo debe ser un valor booleano (true/false)'
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
