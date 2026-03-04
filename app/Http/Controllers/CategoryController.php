<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('ver cursos');
        $categories = Category::withCount('courses')->orderBy('nombre')->paginate(20);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('crear cursos');
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('crear cursos');
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'color'       => 'required|string|max:7',
            'icono'       => 'nullable|string|max:50',
        ]);
        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function show(Category $category)
    {
        $this->authorize('ver cursos');
        $category->load('courses');
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $this->authorize('editar cursos');
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('editar cursos');
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'color'       => 'required|string|max:7',
            'icono'       => 'nullable|string|max:50',
        ]);
        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('eliminar cursos');
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada.');
    }
}
