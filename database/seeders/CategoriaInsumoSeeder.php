<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaInsumoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categoria_insumo')->insertOrIgnore([
            ['idCategoria' => 1, 'nombreCategoria' => 'Carnes y Aves', 'descripcionCategoria' => 'Carnes y aves frescas'],
            ['idCategoria' => 2, 'nombreCategoria' => 'Acompañamientos', 'descripcionCategoria' => 'Acompañamientos varios'],
            ['idCategoria' => 3, 'nombreCategoria' => 'Bebidas', 'descripcionCategoria' => 'Bebidas y refrescos'],
            ['idCategoria' => 4, 'nombreCategoria' => 'Condimentos y Salsas', 'descripcionCategoria' => 'Condimentos y salsas'],
            ['idCategoria' => 5, 'nombreCategoria' => 'Postres', 'descripcionCategoria' => 'Postres y helados'],
        ]);
    }
}
