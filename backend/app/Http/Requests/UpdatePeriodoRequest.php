<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *   schema="UpdatePeriodoRequest",
 *   title="Request Update Periodo",
 *   type="object",
 *   example={
 *      "fecha_inicio": "2022-02-01",
 *      "fecha_final": "2022-07-15"
 *   }
 * )
 */
class UpdatePeriodoRequest extends FormRequest
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
        $periodo = request()->route('periodo');

        return [
            'fecha_inicio' => ['date_format:Y-m-d', Rule::unique('periodos')->ignore($periodo, 'periodo')],
            'fecha_final' => ['date_format:Y-m-d', 'after:fecha_inicio', Rule::unique('periodos')->ignore($periodo, 'periodo')]
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_inicio.date_format' => 'La fecha inicial debe tener formato "YYYY-MM-DD"',
            'fecha_inicio.unique' => 'La fecha inicial ya se encuentra registrada',
            'fecha_final.date_formate' => 'La fecha final debe tener formato "YYYY-MM-DD"',
            'fecha_final.after' => 'La fecha final debe ser posterior a la fecha inicial',
            'fecha_final.unique' => 'La fecha final ya se encuentra registrada'
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
