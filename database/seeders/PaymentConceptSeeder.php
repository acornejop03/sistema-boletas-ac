<?php

namespace Database\Seeders;

use App\Models\PaymentConcept;
use Illuminate\Database\Seeder;

class PaymentConceptSeeder extends Seeder
{
    public function run(): void
    {
        $concepts = [
            ['nombre' => 'MATRICULA',   'descripcion' => 'Matrícula por ciclo/periodo', 'monto_default' => 50.00],
            ['nombre' => 'PENSION',     'descripcion' => 'Pensión mensual',             'monto_default' => 120.00],
            ['nombre' => 'MATERIALES',  'descripcion' => 'Materiales educativos',       'monto_default' => 30.00],
            ['nombre' => 'OTRO',        'descripcion' => 'Otro concepto de pago',       'monto_default' => null],
        ];

        foreach ($concepts as $concept) {
            PaymentConcept::firstOrCreate(
                ['nombre' => $concept['nombre']],
                $concept + ['tipo_afectacion_igv' => '20', 'activo' => true]
            );
        }
    }
}
