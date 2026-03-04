<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('crear matriculas');
    }

    public function rules(): array
    {
        return [
            'student_id'      => ['required', 'exists:students,id'],
            'course_id'       => ['required', 'exists:courses,id'],
            'periodo'         => [
                'required',
                'regex:/^\d{4}-\d{2}$/',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Enrollment::where('student_id', $this->student_id)
                        ->where('course_id', $this->course_id)
                        ->where('periodo', $value)
                        ->exists();
                    if ($exists) {
                        $fail('El alumno ya está matriculado en este curso para el periodo indicado.');
                    }
                },
            ],
            'fecha_matricula' => ['required', 'date'],
            'fecha_inicio'    => ['nullable', 'date'],
            'fecha_fin'       => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'turno'           => ['required', 'in:MAÑANA,TARDE,NOCHE'],
            'observaciones'   => ['nullable', 'string', 'max:1000'],
        ];
    }
}
