<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionStructure = [
            1 => [
                'nombre' => 'admin',
                'descripcion' => 'Administrador del Sistema',
                'permisos' => [
                    'view_dashboard', 'view_ventas', 'view_almacen', 'view_comprobantes', 'view_empleados',
                    'create_venta', 'edit_venta', 'delete_venta',
                    'create_comprobante', 'view_comprobante',
                    'view_almacen_productos', 'create_almacen', 'edit_almacen', 'delete_almacen',
                    'view_reportes', 'export_reportes',
                    'manage_permissions', 'manage_usuarios', 'manage_empleados'
                ]
            ],
            2 => [
                'nombre' => 'mozo',
                'descripcion' => 'Mozo de Sala',
                'permisos' => [
                    'view_dashboard', 'view_ventas', 'view_mesas',
                    'create_venta_sala', 'edit_venta_sala',
                    'view_comprobante', 'create_comprobante_venta'
                ]
            ],
            3 => [
                'nombre' => 'cocinero',
                'descripcion' => 'Cocinero',
                'permisos' => [
                    'view_dashboard', 'view_pedidos_cocina',
                    'update_preparacion_pedido', 'view_detalle_preparacion'
                ]
            ],
            4 => [
                'nombre' => 'jefe_almacen',
                'descripcion' => 'Jefe de Almacén',
                'permisos' => [
                    'view_dashboard', 'view_almacen', 'view_almacen_productos',
                    'create_almacen', 'edit_almacen', 'delete_almacen',
                    'view_proveedores', 'create_compra_almacen',
                    'view_inventario', 'export_inventario'
                ]
            ]
        ];

        $allPermisos = [];
        foreach ($permissionStructure as $tipoEmpleado => $data) {
            foreach ($data['permisos'] as $permiso) {
                if (!in_array($permiso, $allPermisos)) {
                    $allPermisos[] = $permiso;
                    Permission::firstOrCreate(['name' => $permiso]);
                }
            }
        }

        foreach ($permissionStructure as $tipoEmpleado => $data) {
            $role = Role::firstOrCreate(
                ['name' => $data['nombre']],
                ['guard_name' => 'web']
            );
            $role->syncPermissions($data['permisos']);
        }
    }
}
