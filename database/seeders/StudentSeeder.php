<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['nombres' => 'CARLOS ANDRES',   'ap' => 'GARCIA',    'am' => 'LOPEZ',    'dni' => '72345678', 'email' => 'carlos.garcia@mail.com'],
            ['nombres' => 'MARIA ELENA',      'ap' => 'TORRES',    'am' => 'QUISPE',   'dni' => '73456789', 'email' => 'maria.torres@mail.com'],
            ['nombres' => 'JUAN PABLO',       'ap' => 'RAMIREZ',   'am' => 'MENDOZA',  'dni' => '74567890', 'email' => 'juan.ramirez@mail.com'],
            ['nombres' => 'ANA LUCIA',        'ap' => 'FLORES',    'am' => 'VARGAS',   'dni' => '75678901', 'email' => 'ana.flores@mail.com'],
            ['nombres' => 'PEDRO MIGUEL',     'ap' => 'SANCHEZ',   'am' => 'HUAMAN',   'dni' => '76789012', 'email' => 'pedro.sanchez@mail.com'],
            ['nombres' => 'LUCIA FERNANDA',   'ap' => 'DIAZ',      'am' => 'CCOPA',    'dni' => '77890123', 'email' => 'lucia.diaz@mail.com'],
            ['nombres' => 'ROBERTO CARLOS',   'ap' => 'MAMANI',    'am' => 'SILVA',    'dni' => '78901234', 'email' => 'roberto.mamani@mail.com'],
            ['nombres' => 'SOFIA VALENTINA',  'ap' => 'GUTIERREZ', 'am' => 'PALOMINO', 'dni' => '79012345', 'email' => 'sofia.gutierrez@mail.com'],
            ['nombres' => 'DIEGO FERNANDO',   'ap' => 'HERRERA',   'am' => 'ZUNIGA',   'dni' => '70123456', 'email' => 'diego.herrera@mail.com'],
            ['nombres' => 'VALERIA ISABEL',   'ap' => 'LEON',      'am' => 'RAMOS',    'dni' => '71234567', 'email' => 'valeria.leon@mail.com'],
        ];

        foreach ($students as $data) {
            Student::firstOrCreate(
                ['numero_documento' => $data['dni']],
                [
                    'codigo'           => Student::generarCodigo(),
                    'tipo_documento'   => 1, // DNI
                    'numero_documento' => $data['dni'],
                    'nombres'          => $data['nombres'],
                    'apellido_paterno' => $data['ap'],
                    'apellido_materno' => $data['am'],
                    'email'            => $data['email'],
                    'activo'           => true,
                ]
            );
        }
    }
}
