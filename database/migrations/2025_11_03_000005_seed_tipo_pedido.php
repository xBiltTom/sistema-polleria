<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insertar tipos de pedido si no existen
        DB::table('tipo_pedido')->insertOrIgnore([
            ['idTipoPedido' => 1, 'desscripcion' => 'Salón'],
            ['idTipoPedido' => 2, 'desscripcion' => 'Para Llevar'],
            ['idTipoPedido' => 3, 'desscripcion' => 'Delivery'],
        ]);
    }

    public function down(): void
    {
        // No eliminamos datos en el down
    }
};
