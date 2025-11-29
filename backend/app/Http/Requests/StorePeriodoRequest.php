<?php

namespace App\Http\Requests;

use App\Models\Periodo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;

/**
 * @OA\Schema(
 *   schema="StorePeriodoRequest",
 *   title="Request Store Periodo",
 *   type="object",
 *   example={
 *      "periodo": "2023A",
 *      "fecha_inicio": "2023-02-01",
 *      "fecha_final": "2023-07-15"
 *   }
 * )
 */
class StorePeriodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('create', Periodo::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'periodo' => 'required|regex:/^[0-9]{4}[A,B]$/|unique:periodos',
            'fecha_inicio' => 'date_format:Y-m-d|unique:periodos',
            'fecha_final' => 'date_format:Y-m-d|after:fecha_inicio|unique:periodos'
        ];
    }

    public function messages()
    {
        return [
            'periodo.required' => 'Se debe dar un alias del periodo (Ej. 2022A)',
            'periodo.regex' => 'El alias del periodo debe contener el año correspondiente y la letra "A" o "B" según corresponda el semeste',
            'periodo.unique' => 'El alias del periodo ya se encuentra registrado',
            'fecha_inicio.date_format' => 'La fecha inicial debe tener formato "YYYY-MM-DD"',
            'fecha_inicio.unique' => 'La fecha inicial ya se encuentra registrada',
            'fecha_final.date_format' => 'La fecha final debe tener formato "YYYY-MM-DD"',
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
