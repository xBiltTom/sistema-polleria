<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPagoOrdenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_pago_orden')->insertOrIgnore([
            ['idTipoPago' => 1, 'descripcion' => 'Efectivo'],
            ['idTipoPago' => 2, 'descripcion' => 'Transferencia'],
            ['idTipoPago' => 3, 'descripcion' => 'Tarjeta de Crédito'],
            ['idTipoPago' => 4, 'descripcion' => 'Cheque'],
        ]);
    }
}
