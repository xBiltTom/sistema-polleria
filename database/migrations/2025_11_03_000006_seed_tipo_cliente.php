<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar y crear tipo_cliente si no existe
        if (DB::table('tipo_cliente')->count() === 0) {
            DB::table('tipo_cliente')->insertOrIgnore([
                ['idTipo' => 1, 'descripcion' => 'Cliente General'],
                ['idTipo' => 2, 'descripcion' => 'Cliente Mayorista'],
                ['idTipo' => 3, 'descripcion' => 'Cliente Frecuente'],
                ['idTipo' => 4, 'descripcion' => 'Cliente VIP'],
            ]);
        }
    }

    public function down(): void
    {
        // No eliminamos datos en el down
    }
};
