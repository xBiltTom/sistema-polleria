<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plato')->insertOrIgnore([
            // COMBOS
            [
                'idPlato' => 1,
                'nombrePlato' => 'COMBO PRIMAVERAL',
                'descripcion' => '1 Pollo a la brasa, Papas fritas, Ensalada, Cremas',
                'precioVenta' => 34.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 2,
                'nombrePlato' => 'COMBO FAMILIAR',
                'descripcion' => '1 Pollo a la brasa, Papas fritas, Ensalada, Cremas, Gaseosa 1½L',
                'precioVenta' => 36.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 3,
                'nombrePlato' => 'COMBO GLOTÓN',
                'descripcion' => '1 Pollo a la brasa, 1 Chaufa, Papas fritas, Ensalada, Cremas, Gaseosa 1½L',
                'precioVenta' => 41.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 4,
                'nombrePlato' => 'COMBO FENÓMENO',
                'descripcion' => '1 Pollo a la brasa + ¼ Pollo a la brasa, Papas fritas, Ensalada, Cremas, Gaseosa 1½L',
                'precioVenta' => 43.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 5,
                'nombrePlato' => 'COMBO YÁMBOLY',
                'descripcion' => '1 Pollo a la brasa, Papas fritas, Ensalada, Cremas, Gaseosa 1½L, Helado 1L',
                'precioVenta' => 44.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 6,
                'nombrePlato' => 'COMBO DUO COOL',
                'descripcion' => '½ Pollo a la brasa, Papas fritas, Ensalada, Cremas, Gaseosa 750ml, Helado ½L',
                'precioVenta' => 24.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 7,
                'nombrePlato' => 'COMBO RÓMPECABEZAS',
                'descripcion' => '1 Pollo a la brasa, Papas fritas, Ensalada, Cremas, 1 Acompañamiento (2 Pepsi 1L, Arroz chaufa o Papas fritas)',
                'precioVenta' => 39.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 8,
                'nombrePlato' => 'COMBO SOLTERO',
                'descripcion' => '1 Pollo a la brasa, Cremas, Gaseosa 1½L',
                'precioVenta' => 29.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 9,
                'nombrePlato' => 'COMBO DUO',
                'descripcion' => '½ Pollo a la brasa, Papas fritas, Ensalada, Cremas, Gaseosa 750ml',
                'precioVenta' => 20.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 10,
                'nombrePlato' => 'COMBO EJECUTIVO',
                'descripcion' => '¼ Pollo a la brasa, Papas fritas, Ensalada, Cremas, Gaseosa ½L',
                'precioVenta' => 11.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 11,
                'nombrePlato' => 'COMBO LUCHITO',
                'descripcion' => '¼ Pollo a la brasa, Arroz chaufa, Papas fritas, Ensalada, Cremas, Gaseosa Triple Kola ½L',
                'precioVenta' => 14.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
            [
                'idPlato' => 12,
                'nombrePlato' => 'COMBO JUNIOR',
                'descripcion' => '⅛ Pollo a la brasa, Papas fritas, Ensalada, Cremas, Gaseosa',
                'precioVenta' => 7.99,
                'stock' => 0,
                'idCategoria' => 1,
            ],
        ]);
    }
}
