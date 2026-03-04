<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('crear alumnos');
    }

    public function rules(): array
    {
        $studentId = $this->route('student')?->id;

        return [
            'tipo_documento'   => ['required', 'in:0,1,6'],
            'numero_documento' => [
                'nullable',
                'string',
                'max:20',
                "unique:students,numero_documento,{$studentId}",
            ],
            'nombres'          => ['required', 'string', 'max:100'],
            'apellido_paterno' => ['required', 'string', 'max:100'],
            'apellido_materno' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'email'            => ['nullable', 'email', 'max:100'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'nombre_apoderado' => ['nullable', 'string', 'max:200'],
            'telefono_apoderado' => ['nullable', 'string', 'max:20'],
            'direccion'        => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_documento.unique' => 'Ya existe un alumno con este número de documento.',
            'nombres.required'        => 'Los nombres son requeridos.',
            'apellido_paterno.required' => 'El apellido paterno es requerido.',
            'apellido_materno.required' => 'El apellido materno es requerido.',
        ];
    }
}
