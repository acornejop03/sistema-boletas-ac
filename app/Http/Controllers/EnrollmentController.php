<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $this->authorize('ver matriculas');

        $query = Enrollment::with(['student', 'course', 'user']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('student', function ($q) use ($s) {
                $q->where('nombres', 'like', "%{$s}%")
                  ->orWhere('apellido_paterno', 'like', "%{$s}%")
                  ->orWhere('numero_documento', 'like', "%{$s}%");
            });
        }
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('periodo')) {
            $query->where('periodo', $request->periodo);
        }

        $enrollments = $query->latest()->paginate(20)->withQueryString();
        $courses     = Course::where('activo', true)->orderBy('nombre')->get();

        return view('enrollments.index', compact('enrollments', 'courses'));
    }

    public function create()
    {
        $this->authorize('crear matriculas');
        $students = Student::where('activo', true)->orderBy('apellido_paterno')->get();
        $courses  = Course::where('activo', true)->with('category')->orderBy('nombre')->get();
        return view('enrollments.create', compact('students', 'courses'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $data                  = $request->validated();
        $data['user_id']       = auth()->id();
        $data['fecha_matricula'] = $data['fecha_matricula'] ?? now()->toDateString();

        $enrollment = Enrollment::create($data);

        // Si el curso tiene precio de matrícula > 0, generar cobro automáticamente
        $course = Course::find($data['course_id']);
        if ($course && $course->precio_matricula > 0) {
            try {
                $this->paymentService->cobrar([
                    'student_id'       => $data['student_id'],
                    'enrollment_id'    => $enrollment->id,
                    'tipo_pago'        => 'MATRICULA',
                    'periodo_pago'     => null,
                    'forma_pago'       => 'EFECTIVO',
                    'monto'            => $course->precio_matricula,
                    'tipo_comprobante' => '03',
                ], auth()->user());
            } catch (\Exception $e) {
                // Si falla el cobro automático, continuar igual
            }
        }

        return redirect()->route('enrollments.show', $enrollment)
            ->with('success', 'Matrícula registrada correctamente.');
    }

    public function show(Enrollment $enrollment)
    {
        $this->authorize('ver matriculas');
        $enrollment->load(['student', 'course.category', 'user', 'payments.sale']);
        return view('enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $this->authorize('editar matriculas');
        $courses = Course::where('activo', true)->orderBy('nombre')->get();
        return view('enrollments.edit', compact('enrollment', 'courses'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorize('editar matriculas');

        $data = $request->validate([
            'turno'         => 'required|in:MAÑANA,TARDE,NOCHE',
            'estado'        => 'required|in:ACTIVO,CULMINADO,RETIRADO,SUSPENDIDO',
            'fecha_inicio'  => 'nullable|date',
            'fecha_fin'     => 'nullable|date',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $enrollment->update($data);

        return redirect()->route('enrollments.show', $enrollment)
            ->with('success', 'Matrícula actualizada correctamente.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('editar matriculas');
        $enrollment->delete();
        return redirect()->route('enrollments.index')
            ->with('success', 'Matrícula eliminada correctamente.');
    }
}
