<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mesa')->insertOrIgnore([
            // Área 1: Mesas pequeñas (2 personas)
            [
                'idMesa' => 1,
                'nroMesa' => 1,
                'capacidadMesa' => 2,
                'descripcionMesa' => 'Mesa pequeña - Área 1',
                'estado' => 'disponible',
            ],
            [
                'idMesa' => 2,
                'nroMesa' => 2,
                'capacidadMesa' => 2,
                'descripcionMesa' => 'Mesa pequeña - Área 1',
                'estado' => 'disponible',
            ],
            [
                'idMesa' => 3,
                'nroMesa' => 3,
                'capacidadMesa' => 2,
                'descripcionMesa' => 'Mesa pequeña - Área 1',
                'estado' => 'disponible',
            ],

            // Área 2: Mesas medianas (4 personas)
            [
                'idMesa' => 4,
                'nroMesa' => 4,
                'capacidadMesa' => 4,
                'descripcionMesa' => 'Mesa mediana - Área 2',
                'estado' => 'disponible',
            ],
            [
                'idMesa' => 5,
                'nroMesa' => 5,
                'capacidadMesa' => 4,
                'descripcionMesa' => 'Mesa mediana - Área 2',
                'estado' => 'disponible',
            ],
            [
                'idMesa' => 6,
                'nroMesa' => 6,
                'capacidadMesa' => 4,
                'descripcionMesa' => 'Mesa mediana - Área 2',
                'estado' => 'disponible',
            ],
            [
                'idMesa' => 7,
                'nroMesa' => 7,
                'capacidadMesa' => 4,
                'descripcionMesa' => 'Mesa mediana - Área 2',
                'estado' => 'disponible',
            ],

            // Área 3: Mesas grandes (6 personas)
            [
                'idMesa' => 8,
                'nroMesa' => 8,
                'capacidadMesa' => 6,
                'descripcionMesa' => 'Mesa grande - Área 3',
                'estado' => 'disponible',
            ],
            [
                'idMesa' => 9,
                'nroMesa' => 9,
                'capacidadMesa' => 6,
                'descripcionMesa' => 'Mesa grande - Área 3',
                'estado' => 'disponible',
            ],
            [
                'idMesa' => 10,
                'nroMesa' => 10,
                'capacidadMesa' => 6,
                'descripcionMesa' => 'Mesa grande - Área 3',
                'estado' => 'disponible',
            ],
        ]);
    }
}
