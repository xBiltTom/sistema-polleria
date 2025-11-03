<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUsersCommand extends Command
{
    protected $signature = 'users:create';
    protected $description = 'Crear usuarios de prueba para cada tipo de empleado';

    public function handle()
    {
        $usuarios = [
            [
                'name' => 'Admin Sistema',
                'email' => 'admin@polleria.com',
                'password' => 'admin123',
                'tipo_empleado_id' => 1,
            ],
            [
                'name' => 'Carlos Mozo',
                'email' => 'mozo@polleria.com',
                'password' => 'mozo123',
                'tipo_empleado_id' => 2,
            ],
            [
                'name' => 'Juan Cocinero',
                'email' => 'cocinero@polleria.com',
                'password' => 'cocina123',
                'tipo_empleado_id' => 3,
            ],
            [
                'name' => 'Pedro Almacén',
                'email' => 'almacen@polleria.com',
                'password' => 'almacen123',
                'tipo_empleado_id' => 4,
            ],
        ];

        foreach ($usuarios as $usuarioData) {
            $tipoEmpleadoId = $usuarioData['tipo_empleado_id'];
            unset($usuarioData['tipo_empleado_id']);

            // Crear o buscar usuario
            $user = User::where('email', $usuarioData['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $usuarioData['name'],
                    'email' => $usuarioData['email'],
                    'password' => Hash::make($usuarioData['password']),
                    'email_verified_at' => now(),
                ]);
                $this->info("✓ Usuario creado: {$usuarioData['email']}");
            } else {
                $this->info("⚠ Usuario ya existe: {$usuarioData['email']}");
            }

            // Crear o actualizar empleado
            Empleado::updateOrCreate(
                ['usuario_id' => $user->id],
                [
                    'tipo_empleado_id' => $tipoEmpleadoId,
                    'nombre' => $usuarioData['name'],
                    'estado' => 'activo',
                ]
            );
        }

        $this->info("\n✓ Usuarios creados exitosamente");
    }
}
