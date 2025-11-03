<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_preparacion', function (Blueprint $table) {
            $table->integer('idDetallePreparacion')->primary()->autoIncrement();
            $table->integer('idDetalle');
            $table->integer('idPedido');
            $table->integer('idInsumo');
            $table->decimal('precioInsumo', 10, 2);
            $table->timestamps();

            $table->foreign('idInsumo')->references('idInsumo')->on('insumo');
            $table->foreign(['idDetalle', 'idPedido'])
                ->references(['idDetalle', 'idPedido'])
                ->on('preparacion_plato')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_preparacion');
    }
};
