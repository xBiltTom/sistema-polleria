<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categoria_producto')->insertOrIgnore([
            ['idCategoriaProducto' => 1, 'descripcion' => 'Pollos'],
            ['idCategoriaProducto' => 2, 'descripcion' => 'Acompañamientos'],
            ['idCategoriaProducto' => 3, 'descripcion' => 'Bebidas'],
            ['idCategoriaProducto' => 4, 'descripcion' => 'Postres'],
        ]);
    }
}
