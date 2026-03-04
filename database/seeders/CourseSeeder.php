<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // Matemáticas
            ['categoria' => 'Matemáticas', 'codigo' => 'MAT-BAS', 'nombre' => 'Matemáticas Básicas',
             'nivel' => 'BASICO', 'duracion_meses' => 3, 'precio_matricula' => 50.00, 'precio_pension' => 120.00],
            ['categoria' => 'Matemáticas', 'codigo' => 'MAT-AVZ', 'nombre' => 'Matemáticas Avanzadas',
             'nivel' => 'AVANZADO', 'duracion_meses' => 4, 'precio_matricula' => 60.00, 'precio_pension' => 150.00],

            // Inglés
            ['categoria' => 'Inglés', 'codigo' => 'ING-BAS', 'nombre' => 'Inglés Básico',
             'nivel' => 'BASICO', 'duracion_meses' => 3, 'precio_matricula' => 50.00, 'precio_pension' => 130.00],
            ['categoria' => 'Inglés', 'codigo' => 'ING-INT', 'nombre' => 'Inglés Intermedio',
             'nivel' => 'INTERMEDIO', 'duracion_meses' => 4, 'precio_matricula' => 60.00, 'precio_pension' => 160.00],

            // Computación
            ['categoria' => 'Computación', 'codigo' => 'COMP-BAS', 'nombre' => 'Computación Básica',
             'nivel' => 'BASICO', 'duracion_meses' => 2, 'precio_matricula' => 40.00, 'precio_pension' => 110.00],
            ['categoria' => 'Computación', 'codigo' => 'PROG-WEB', 'nombre' => 'Programación Web',
             'nivel' => 'INTERMEDIO', 'duracion_meses' => 5, 'precio_matricula' => 80.00, 'precio_pension' => 200.00],

            // Comunicación
            ['categoria' => 'Comunicación', 'codigo' => 'COM-ORA', 'nombre' => 'Oratoria y Comunicación',
             'nivel' => 'BASICO', 'duracion_meses' => 2, 'precio_matricula' => 40.00, 'precio_pension' => 100.00],
            ['categoria' => 'Comunicación', 'codigo' => 'COM-RED', 'nombre' => 'Redacción Avanzada',
             'nivel' => 'INTERMEDIO', 'duracion_meses' => 3, 'precio_matricula' => 50.00, 'precio_pension' => 120.00],

            // Ciencias
            ['categoria' => 'Ciencias', 'codigo' => 'CIE-FIS', 'nombre' => 'Física General',
             'nivel' => 'BASICO', 'duracion_meses' => 4, 'precio_matricula' => 60.00, 'precio_pension' => 140.00],
            ['categoria' => 'Ciencias', 'codigo' => 'CIE-QUI', 'nombre' => 'Química General',
             'nivel' => 'BASICO', 'duracion_meses' => 4, 'precio_matricula' => 60.00, 'precio_pension' => 140.00],

            // Arte
            ['categoria' => 'Arte', 'codigo' => 'ART-DIB', 'nombre' => 'Dibujo y Pintura',
             'nivel' => 'BASICO', 'duracion_meses' => 3, 'precio_matricula' => 40.00, 'precio_pension' => 90.00],
            ['categoria' => 'Arte', 'codigo' => 'ART-DIG', 'nombre' => 'Diseño Digital',
             'nivel' => 'INTERMEDIO', 'duracion_meses' => 4, 'precio_matricula' => 60.00, 'precio_pension' => 150.00],

            // Música
            ['categoria' => 'Música', 'codigo' => 'MUS-GUI', 'nombre' => 'Guitarra Básica',
             'nivel' => 'BASICO', 'duracion_meses' => 3, 'precio_matricula' => 50.00, 'precio_pension' => 110.00],
            ['categoria' => 'Música', 'codigo' => 'MUS-PIA', 'nombre' => 'Piano Clásico',
             'nivel' => 'BASICO', 'duracion_meses' => 4, 'precio_matricula' => 60.00, 'precio_pension' => 130.00],

            // Deportes
            ['categoria' => 'Deportes', 'codigo' => 'DEP-FUT', 'nombre' => 'Fútbol Formativo',
             'nivel' => 'BASICO', 'duracion_meses' => 3, 'precio_matricula' => 30.00, 'precio_pension' => 80.00],
            ['categoria' => 'Deportes', 'codigo' => 'DEP-NAT', 'nombre' => 'Natación',
             'nivel' => 'BASICO', 'duracion_meses' => 3, 'precio_matricula' => 40.00, 'precio_pension' => 100.00],
        ];

        foreach ($courses as $data) {
            $category = Category::where('nombre', $data['categoria'])->first();
            if (!$category) continue;

            Course::firstOrCreate(
                ['codigo' => $data['codigo']],
                [
                    'category_id'         => $category->id,
                    'nombre'              => $data['nombre'],
                    'nivel'               => $data['nivel'],
                    'duracion_meses'      => $data['duracion_meses'],
                    'precio_matricula'    => $data['precio_matricula'],
                    'precio_pension'      => $data['precio_pension'],
                    'precio_materiales'   => 0.00,
                    'afecto_igv'          => false,
                    'tipo_afectacion_igv' => '20',
                    'activo'              => true,
                ]
            );
        }
    }
}
