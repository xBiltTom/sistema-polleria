<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            // Agregar soporte para productos además de platos
            $table->integer('idProducto')->nullable()->after('idPlato');
            $table->foreign('idProducto')->references('idProducto')->on('producto')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign(['idProducto']);
            $table->dropColumn('idProducto');
        });
    }
};
