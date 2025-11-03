<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            [
                'name' => 'Admin Sistema',
                'email' => 'admin@polleria.com',
                'password' => 'admin123',
                'tipo_empleado_id' => 1,
                'dni' => '12345678',
                'apellido' => 'Sistema',
                'celular' => '987654321',
            ],
            [
                'name' => 'Carlos Mozo',
                'email' => 'mozo@polleria.com',
                'password' => 'mozo123',
                'tipo_empleado_id' => 2,
                'dni' => '87654321',
                'apellido' => 'Mozo',
                'celular' => '987654322',
            ],
            [
                'name' => 'Juan Cocinero',
                'email' => 'cocinero@polleria.com',
                'password' => 'cocina123',
                'tipo_empleado_id' => 3,
                'dni' => '11111111',
                'apellido' => 'Cocinero',
                'celular' => '987654323',
            ],
            [
                'name' => 'Pedro Almacén',
                'email' => 'almacen@polleria.com',
                'password' => 'almacen123',
                'tipo_empleado_id' => 4,
                'dni' => '22222222',
                'apellido' => 'Almacén',
                'celular' => '987654324',
            ],
        ];

        foreach ($usuarios as $usuarioData) {
            $tipoEmpleadoId = $usuarioData['tipo_empleado_id'];
            $dni = $usuarioData['dni'];
            $apellido = $usuarioData['apellido'];
            $celular = $usuarioData['celular'];
            unset($usuarioData['tipo_empleado_id'], $usuarioData['dni'], $usuarioData['apellido'], $usuarioData['celular']);

            $user = User::firstOrCreate(
                ['email' => $usuarioData['email']],
                [
                    'name' => $usuarioData['name'],
                    'password' => Hash::make($usuarioData['password']),
                    'email_verified_at' => now(),
                ]
            );

            // Obtener el próximo ID disponible
            $nextId = DB::table('empleado')->max('idEmpleado') + 1;

            Empleado::updateOrCreate(
                ['idUsuario' => $user->id],
                [
                    'idEmpleado' => $nextId,
                    'dniEmpleado' => $dni,
                    'nombreEmpleado' => $usuarioData['name'],
                    'apellidoEmpleado' => $apellido,
                    'nroCelular' => $celular,
                    'estado' => 1,
                    'idHorario' => 1,
                    'idTipoEmpleado' => $tipoEmpleadoId,
                ]
            );
        }
    }
}
