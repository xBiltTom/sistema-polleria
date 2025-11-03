<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('insumo')->insertOrIgnore([
            // Carnes y Aves
            [
                'idInsumo' => 1,
                'nombreInsumo' => 'Pollo Fresco',
                'precioInsumo' => 15.00,
                'stock' => 0,
                'idCategoria' => 1,
            ],

            // Acompañamientos
            [
                'idInsumo' => 2,
                'nombreInsumo' => 'Papa',
                'precioInsumo' => 2.00,
                'stock' => 0,
                'idCategoria' => 2,
            ],
            [
                'idInsumo' => 3,
                'nombreInsumo' => 'Tomate',
                'precioInsumo' => 3.00,
                'stock' => 0,
                'idCategoria' => 2,
            ],
            [
                'idInsumo' => 4,
                'nombreInsumo' => 'Cebolla',
                'precioInsumo' => 2.50,
                'stock' => 0,
                'idCategoria' => 2,
            ],
            [
                'idInsumo' => 5,
                'nombreInsumo' => 'Lechuga',
                'precioInsumo' => 2.00,
                'stock' => 0,
                'idCategoria' => 2,
            ],
            [
                'idInsumo' => 6,
                'nombreInsumo' => 'Arroz',
                'precioInsumo' => 4.00,
                'stock' => 0,
                'idCategoria' => 2,
            ],

            // Bebidas
            [
                'idInsumo' => 7,
                'nombreInsumo' => 'Gaseosa 1½L (Caja 12)',
                'precioInsumo' => 78.00,
                'stock' => 0,
                'idCategoria' => 3,
            ],
            [
                'idInsumo' => 8,
                'nombreInsumo' => 'Gaseosa 750ml (Caja 24)',
                'precioInsumo' => 96.00,
                'stock' => 0,
                'idCategoria' => 3,
            ],
            [
                'idInsumo' => 9,
                'nombreInsumo' => 'Triple Kola ½L (Caja 24)',
                'precioInsumo' => 48.00,
                'stock' => 0,
                'idCategoria' => 3,
            ],
            [
                'idInsumo' => 10,
                'nombreInsumo' => 'Pepsi 1L (Caja 12)',
                'precioInsumo' => 60.00,
                'stock' => 0,
                'idCategoria' => 3,
            ],

            // Condimentos y Salsas
            [
                'idInsumo' => 11,
                'nombreInsumo' => 'Sal de Pollo',
                'precioInsumo' => 8.00,
                'stock' => 0,
                'idCategoria' => 4,
            ],
            [
                'idInsumo' => 12,
                'nombreInsumo' => 'Ajo',
                'precioInsumo' => 5.00,
                'stock' => 0,
                'idCategoria' => 4,
            ],
            [
                'idInsumo' => 13,
                'nombreInsumo' => 'Mayonesa',
                'precioInsumo' => 10.00,
                'stock' => 0,
                'idCategoria' => 4,
            ],
            [
                'idInsumo' => 14,
                'nombreInsumo' => 'Limón',
                'precioInsumo' => 3.50,
                'stock' => 0,
                'idCategoria' => 4,
            ],

            // Postres
            [
                'idInsumo' => 15,
                'nombreInsumo' => 'Helado 1L',
                'precioInsumo' => 12.00,
                'stock' => 0,
                'idCategoria' => 5,
            ],
            [
                'idInsumo' => 16,
                'nombreInsumo' => 'Helado ½L',
                'precioInsumo' => 6.50,
                'stock' => 0,
                'idCategoria' => 5,
            ],
        ]);
    }
}
