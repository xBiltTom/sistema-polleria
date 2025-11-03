<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaPlatoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categoria_plato')->insertOrIgnore([
            ['idCategoriaPlato' => 1, 'descripción' => 'Combos'],
            ['idCategoriaPlato' => 2, 'descripción' => 'Porciones'],
            ['idCategoriaPlato' => 3, 'descripción' => 'Acompañamientos'],
        ]);
    }
}
