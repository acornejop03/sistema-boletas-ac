<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('ver alumnos');

        $query = Student::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nombres', 'like', "%{$s}%")
                  ->orWhere('apellido_paterno', 'like', "%{$s}%")
                  ->orWhere('apellido_materno', 'like', "%{$s}%")
                  ->orWhere('numero_documento', 'like', "%{$s}%")
                  ->orWhere('codigo', 'like', "%{$s}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado === 'activo');
        }

        $students = $query->with(['enrollments'])->orderBy('apellido_paterno')->paginate(20)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $this->authorize('crear alumnos');
        return view('students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $data           = $request->validated();
        $data['codigo'] = Student::generarCodigo();

        $student = Student::create($data);

        return redirect()->route('students.show', $student)
            ->with('success', "Alumno {$student->nombre_completo} registrado correctamente. Código: {$student->codigo}");
    }

    public function show(Student $student)
    {
        $this->authorize('ver alumnos');
        $student->load([
            'enrollments.course.category',
            'payments.sale',
            'sales.items',
        ]);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $this->authorize('editar alumnos');
        return view('students.edit', compact('student'));
    }

    public function update(StoreStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        return redirect()->route('students.show', $student)
            ->with('success', 'Datos del alumno actualizados correctamente.');
    }

    public function destroy(Student $student)
    {
        $this->authorize('eliminar alumnos');
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Alumno eliminado correctamente.');
    }

    // AJAX: buscar alumno por DNI, código o nombre
    public function buscar(Request $request)
    {
        $q = $request->get('q', '');

        $students = Student::where('activo', true)
            ->where(function ($query) use ($q) {
                $query->where('numero_documento', 'like', "%{$q}%")
                      ->orWhere('codigo', 'like', "%{$q}%")
                      ->orWhere('nombres', 'like', "%{$q}%")
                      ->orWhere('apellido_paterno', 'like', "%{$q}%");
            })
            ->with(['enrollments' => function ($query) {
                $query->where('estado', 'ACTIVO')->with('course');
            }])
            ->limit(10)
            ->get()
            ->map(function ($s) {
                return [
                    'id'               => $s->id,
                    'codigo'           => $s->codigo,
                    'nombre_completo'  => $s->nombre_completo,
                    'tipo_doc_nombre'  => $s->tipo_doc_nombre,
                    'numero_documento' => $s->numero_documento,
                    'foto_path'        => $s->foto_path,
                    'enrollments'      => $s->enrollments->map(function ($e) {
                        return [
                            'id'          => $e->id,
                            'course_id'   => $e->course_id,
                            'curso'       => $e->course->nombre,
                            'periodo'     => $e->periodo,
                            'precio_pension'   => $e->course->precio_pension,
                            'precio_matricula' => $e->course->precio_matricula,
                            'precio_materiales'=> $e->course->precio_materiales,
                        ];
                    }),
                ];
            });

        return response()->json($students);
    }

    // AJAX: matrículas activas de un alumno
    public function matriculasActivas(Student $student)
    {
        $enrollments = $student->enrollments()
            ->where('estado', 'ACTIVO')
            ->with('course')
            ->get()
            ->map(function ($e) {
                return [
                    'id'               => $e->id,
                    'curso'            => $e->course->nombre,
                    'periodo'          => $e->periodo,
                    'precio_pension'   => $e->course->precio_pension,
                    'precio_matricula' => $e->course->precio_matricula,
                    'precio_materiales'=> $e->course->precio_materiales,
                    'afecto_igv'       => $e->course->afecto_igv,
                ];
            });

        return response()->json($enrollments);
    }

    public function payments(Student $student)
    {
        $this->authorize('ver alumnos');
        $payments = $student->payments()->with('sale', 'enrollment.course')->latest()->paginate(20);
        return view('students.payments', compact('student', 'payments'));
    }
}
