<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin
        $superadmin = \App\Models\User::firstOrCreate([
            'email' => 'superadmin@academia.com'
        ], [
            'name' => 'Super Administrador',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $superadmin->assignRole('superadmin');

        // Administrador
        $admin = \App\Models\User::firstOrCreate([
            'email' => 'admin@academia.com'
        ], [
            'name' => 'Administrador',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $admin->assignRole('administrador');

        // Cajero
        $cajero = \App\Models\User::firstOrCreate([
            'email' => 'cajero@academia.com'
        ], [
            'name' => 'Cajero Principal',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $cajero->assignRole('cajero');

        // Consulta (Solo lectura)
        $consulta = \App\Models\User::firstOrCreate([
            'email' => 'consulta@academia.com'
        ], [
            'name' => 'Usuario Consulta',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $consulta->assignRole('consulta');
    }
}
