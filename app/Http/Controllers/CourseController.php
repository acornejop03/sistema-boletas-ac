<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('ver cursos');
        $query = Course::with('category');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nombre', 'like', "%{$s}%")
                  ->orWhere('codigo', 'like', "%{$s}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $courses    = $query->orderBy('nombre')->paginate(20)->withQueryString();
        $categories = Category::where('activo', true)->orderBy('nombre')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function create()
    {
        $this->authorize('crear cursos');
        $categories = Category::where('activo', true)->orderBy('nombre')->get();
        return view('courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('crear cursos');

        $data = $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'codigo'             => 'required|string|max:20|unique:courses',
            'nombre'             => 'required|string|max:200',
            'descripcion'        => 'nullable|string',
            'nivel'              => 'required|in:BASICO,INTERMEDIO,AVANZADO',
            'duracion_meses'     => 'required|integer|min:1',
            'duracion_horas'     => 'nullable|integer',
            'precio_matricula'   => 'required|numeric|min:0',
            'precio_pension'     => 'required|numeric|min:0',
            'precio_materiales'  => 'required|numeric|min:0',
            'afecto_igv'         => 'boolean',
            'tipo_afectacion_igv'=> 'required|in:10,20,30',
            'max_alumnos'        => 'nullable|integer',
        ]);

        $data['afecto_igv'] = $request->boolean('afecto_igv');

        $course = Course::create($data);

        return redirect()->route('courses.index')
            ->with('success', "Curso '{$course->nombre}' creado correctamente.");
    }

    public function show(Course $course)
    {
        $this->authorize('ver cursos');
        $course->load('category', 'enrollments.student');
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorize('editar cursos');
        $categories = Category::where('activo', true)->orderBy('nombre')->get();
        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('editar cursos');

        $data = $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'codigo'             => "required|string|max:20|unique:courses,codigo,{$course->id}",
            'nombre'             => 'required|string|max:200',
            'descripcion'        => 'nullable|string',
            'nivel'              => 'required|in:BASICO,INTERMEDIO,AVANZADO',
            'duracion_meses'     => 'required|integer|min:1',
            'duracion_horas'     => 'nullable|integer',
            'precio_matricula'   => 'required|numeric|min:0',
            'precio_pension'     => 'required|numeric|min:0',
            'precio_materiales'  => 'required|numeric|min:0',
            'afecto_igv'         => 'boolean',
            'tipo_afectacion_igv'=> 'required|in:10,20,30',
            'max_alumnos'        => 'nullable|integer',
        ]);

        $data['afecto_igv'] = $request->boolean('afecto_igv');
        $course->update($data);

        return redirect()->route('courses.index')
            ->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course)
    {
        $this->authorize('eliminar cursos');
        $course->delete();
        return redirect()->route('courses.index')
            ->with('success', 'Curso eliminado correctamente.');
    }

    // AJAX: obtener precio del curso
    public function precio(Course $course)
    {
        return response()->json([
            'precio_matricula'    => $course->precio_matricula,
            'precio_pension'      => $course->precio_pension,
            'precio_materiales'   => $course->precio_materiales,
            'afecto_igv'          => $course->afecto_igv,
            'tipo_afectacion_igv' => $course->tipo_afectacion_igv,
        ]);
    }
}
