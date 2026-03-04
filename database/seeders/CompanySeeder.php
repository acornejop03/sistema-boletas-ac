<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(
            ['ruc' => '20000000001'],
            [
                'razon_social'    => 'ACADEMIA AC SAC',
                'nombre_comercial'=> 'ACADEMIA AC',
                'ubigeo'          => '150101',
                'departamento'    => 'LIMA',
                'provincia'       => 'LIMA',
                'distrito'        => 'LIMA',
                'direccion'       => 'AV. EDUCACIÓN 456, SAN ISIDRO',
                'telefono'        => '01-2345678',
                'email'           => 'info@academiaac.edu.pe',
                'serie_boleta'    => 'B001',
                'serie_factura'   => 'F001',
                'activo'          => true,
            ]
        );
    }
}
