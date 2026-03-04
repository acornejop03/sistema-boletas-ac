<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Matemáticas',  'color' => '#EF4444', 'icono' => '🔢'],
            ['nombre' => 'Inglés',        'color' => '#3B82F6', 'icono' => '🌐'],
            ['nombre' => 'Computación',   'color' => '#8B5CF6', 'icono' => '💻'],
            ['nombre' => 'Comunicación',  'color' => '#F59E0B', 'icono' => '📖'],
            ['nombre' => 'Ciencias',      'color' => '#10B981', 'icono' => '🔬'],
            ['nombre' => 'Arte',          'color' => '#EC4899', 'icono' => '🎨'],
            ['nombre' => 'Música',        'color' => '#6366F1', 'icono' => '🎵'],
            ['nombre' => 'Deportes',      'color' => '#F97316', 'icono' => '⚽'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['nombre' => $cat['nombre']], $cat + ['activo' => true]);
        }
    }
}
