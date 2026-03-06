<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'student_id'       => ['required', 'exists:students,id'],
            'enrollment_id'    => ['nullable', 'exists:enrollments,id'],
            'tipo_pago'        => ['required', 'in:MATRICULA,PENSION,MATERIALES,OTRO'],
            'periodo_pago'     => ['required_if:tipo_pago,PENSION', 'nullable', 'regex:/^\d{4}-\d{2}$/'],
            'descripcion_pago' => ['nullable', 'string', 'max:500'],
            'forma_pago'       => ['required', 'in:EFECTIVO,TARJETA,TRANSFERENCIA,YAPE,PLIN'],
            'numero_operacion' => ['required_if:forma_pago,TRANSFERENCIA', 'nullable', 'string', 'max:50'],
            'monto'            => ['required', 'numeric', 'min:1'],
            'tipo_comprobante' => ['required', 'in:01,03,NV'],
            'observaciones'    => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required'    => 'Debe seleccionar un alumno.',
            'tipo_pago.required'     => 'Debe seleccionar el tipo de cobro.',
            'periodo_pago.required_if' => 'El periodo es requerido para pensiones.',
            'periodo_pago.regex'     => 'El periodo debe tener formato YYYY-MM.',
            'forma_pago.required'    => 'Debe seleccionar la forma de pago.',
            'numero_operacion.required_if' => 'El número de operación es requerido para transferencias.',
            'monto.required'         => 'Debe ingresar el monto.',
            'monto.min'              => 'El monto debe ser mayor a 0.',
        ];
    }
}
