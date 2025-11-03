<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('add_producto', function (Blueprint $table) {
            $table->integer('idDetalleOpAddProducto');
            $table->integer('idOrden');
            $table->integer('idProducto');
            $table->integer('cantidad')->nullable();
            $table->float('precio')->nullable();
            $table->dateTime('timestamps')->nullable();

            $table->primary(['idDetalleOpAddProducto', 'idOrden']);
            $table->foreign('idOrden')->references('idOrden')->on('orden_abastecimiento');
            $table->foreign('idProducto')->references('idProducto')->on('producto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_producto');
    }
};
