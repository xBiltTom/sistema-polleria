<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si existe la columna precioInsumo
        if (!Schema::hasColumn('insumo', 'precioInsumo')) {
            Schema::table('insumo', function (Blueprint $table) {
                $table->decimal('precioInsumo', 10, 2)->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::table('insumo', function (Blueprint $table) {
            $table->dropColumn('precioInsumo');
        });
    }
};
