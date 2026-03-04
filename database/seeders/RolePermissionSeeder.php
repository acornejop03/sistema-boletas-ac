<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Alumnos
            'ver alumnos', 'crear alumnos', 'editar alumnos', 'eliminar alumnos',
            // Cursos
            'ver cursos', 'crear cursos', 'editar cursos', 'eliminar cursos',
            // Matrículas
            'ver matriculas', 'crear matriculas', 'editar matriculas',
            // Cobros/Pagos
            'ver cobros', 'crear cobros', 'anular cobros', 'ver cobros otros usuarios',
            // Comprobantes SUNAT
            'ver comprobantes', 'emitir comprobantes', 'reenviar comprobantes',
            'descargar xml', 'descargar pdf',
            // Reportes
            'ver reportes basicos', 'ver reportes completos', 'exportar reportes',
            // Configuración
            'ver configuracion', 'editar configuracion', 'gestionar usuarios',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Crear roles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        $cajero     = Role::firstOrCreate(['name' => 'cajero', 'guard_name' => 'web']);
        $consulta   = Role::firstOrCreate(['name' => 'consulta', 'guard_name' => 'web']);

        // SUPERADMIN → todos los permisos
        $superadmin->syncPermissions(Permission::all());

        // ADMINISTRADOR → todo excepto gestionar usuarios y editar configuracion
        $adminPerms = Permission::whereNotIn('name', ['gestionar usuarios', 'editar configuracion'])->get();
        $admin->syncPermissions($adminPerms);

        // CAJERO → permisos limitados de caja
        $cajero->syncPermissions([
            'ver alumnos', 'crear cobros', 'ver cobros',
            'emitir comprobantes', 'ver comprobantes', 'descargar pdf',
            'ver reportes basicos',
        ]);

        // CONSULTA → solo lectura
        $consulta->syncPermissions([
            'ver alumnos', 'ver cobros otros usuarios', 'ver comprobantes',
            'ver reportes basicos', 'ver reportes completos', 'descargar pdf',
            'ver cursos', 'ver matriculas',
        ]);
    }
}
