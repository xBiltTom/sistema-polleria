<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPedidoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_pedido')->insertOrIgnore([
            ['idTipoPedido' => 1, 'descripcionTipoPedido' => 'Salón'],
            ['idTipoPedido' => 2, 'descripcionTipoPedido' => 'Para Llevar'],
            ['idTipoPedido' => 3, 'descripcionTipoPedido' => 'Delivery'],
        ]);
    }
}
