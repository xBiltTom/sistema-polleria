<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoClienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_cliente')->insertOrIgnore([
            ['idTipo' => 1, 'descripcion' => 'Cliente General'],
            ['idTipo' => 2, 'descripcion' => 'Cliente Mayorista'],
            ['idTipo' => 3, 'descripcion' => 'Cliente Frecuente'],
            ['idTipo' => 4, 'descripcion' => 'Cliente VIP'],
        ]);
    }
}
