<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoEmpleadoSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['idTipoEmpleado' => 1, 'descripcion' => 'Admin'],
            ['idTipoEmpleado' => 2, 'descripcion' => 'Mozo'],
            ['idTipoEmpleado' => 3, 'descripcion' => 'Cocinero'],
            ['idTipoEmpleado' => 4, 'descripcion' => 'Jefe de Almacén'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_empleado')->insertOrIgnore($tipo);
        }
    }
}
