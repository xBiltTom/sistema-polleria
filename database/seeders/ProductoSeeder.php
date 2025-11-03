<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('producto')->insertOrIgnore([
            // Pollos
            [
                'idProducto' => 1,
                'nombre' => 'Pollo Entero a la Brasa',
                'descripcion' => 'Pollo completo marinado y asado a la brasa',
                'precioVenta' => 35.00,
                'stock' => 0,
                'idCategoriaProducto' => 1,
            ],
            [
                'idProducto' => 2,
                'nombre' => 'Medio Pollo a la Brasa',
                'descripcion' => 'Medio pollo marinado y asado a la brasa',
                'precioVenta' => 20.00,
                'stock' => 0,
                'idCategoriaProducto' => 1,
            ],
            [
                'idProducto' => 3,
                'nombre' => 'Cuarto de Pollo a la Brasa',
                'descripcion' => 'Cuarto de pollo marinado y asado a la brasa',
                'precioVenta' => 12.00,
                'stock' => 0,
                'idCategoriaProducto' => 1,
            ],
            [
                'idProducto' => 4,
                'nombre' => 'Octavo de Pollo a la Brasa',
                'descripcion' => 'Octavo de pollo marinado y asado a la brasa',
                'precioVenta' => 8.00,
                'stock' => 0,
                'idCategoriaProducto' => 1,
            ],

            // Acompañamientos
            [
                'idProducto' => 5,
                'nombre' => 'Papas Fritas',
                'descripcion' => 'Papas fritas caseras crujientes',
                'precioVenta' => 5.00,
                'stock' => 0,
                'idCategoriaProducto' => 2,
            ],
            [
                'idProducto' => 6,
                'nombre' => 'Ensalada',
                'descripcion' => 'Ensalada fresca con tomate y cebolla',
                'precioVenta' => 3.00,
                'stock' => 0,
                'idCategoriaProducto' => 2,
            ],
            [
                'idProducto' => 7,
                'nombre' => 'Cremas',
                'descripcion' => 'Cremas caseras variadas',
                'precioVenta' => 2.50,
                'stock' => 0,
                'idCategoriaProducto' => 2,
            ],
            [
                'idProducto' => 8,
                'nombre' => 'Arroz Chaufa',
                'descripcion' => 'Arroz chaufa con sabor oriental',
                'precioVenta' => 8.00,
                'stock' => 0,
                'idCategoriaProducto' => 2,
            ],

            // Bebidas
            [
                'idProducto' => 9,
                'nombre' => 'Gaseosa 1½ L',
                'descripcion' => 'Gaseosa en botella de 1.5 litros',
                'precioVenta' => 6.50,
                'stock' => 0,
                'idCategoriaProducto' => 3,
            ],
            [
                'idProducto' => 10,
                'nombre' => 'Gaseosa 750 ml',
                'descripcion' => 'Gaseosa en botella de 750 ml',
                'precioVenta' => 4.00,
                'stock' => 0,
                'idCategoriaProducto' => 3,
            ],
            [
                'idProducto' => 11,
                'nombre' => 'Gaseosa ½ L',
                'descripcion' => 'Gaseosa en botella de 500 ml',
                'precioVenta' => 2.50,
                'stock' => 0,
                'idCategoriaProducto' => 3,
            ],
            [
                'idProducto' => 12,
                'nombre' => 'Triple Kola ½ L',
                'descripcion' => 'Triple Kola en botella de 500 ml',
                'precioVenta' => 2.00,
                'stock' => 0,
                'idCategoriaProducto' => 3,
            ],
            [
                'idProducto' => 13,
                'nombre' => 'Pepsi 1L',
                'descripcion' => 'Pepsi en botella de 1 litro',
                'precioVenta' => 5.00,
                'stock' => 0,
                'idCategoriaProducto' => 3,
            ],

            // Postres
            [
                'idProducto' => 14,
                'nombre' => 'Helado 1L',
                'descripcion' => 'Helado variado de 1 litro',
                'precioVenta' => 12.00,
                'stock' => 0,
                'idCategoriaProducto' => 4,
            ],
            [
                'idProducto' => 15,
                'nombre' => 'Helado ½L',
                'descripcion' => 'Helado variado de 500 ml',
                'precioVenta' => 7.00,
                'stock' => 0,
                'idCategoriaProducto' => 4,
            ],
        ]);
    }
}
