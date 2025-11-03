<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TipoEmpleadoSeeder::class,
            HorarioSeeder::class,
            TipoPagoOrdenSeeder::class,
            TipoClienteSeeder::class,
            TipoPedidoSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            CategoriaProductoSeeder::class,
            CategoriaInsumoSeeder::class,
            CategoriaPlatoSeeder::class,
            ProductoSeeder::class,
            InsumoSeeder::class,
            PlatoSeeder::class,
            MesaSeeder::class,
        ]);
    }
}
