<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preparacion_plato', function (Blueprint $table) {
            $table->integer('idDetalle');
            $table->integer('idPedido');
            $table->string('estado', 45)->nullable();
            $table->integer('idCocinero');
            $table->timestamp('fechaInicio')->nullable();
            $table->timestamp('fechaFin')->nullable();
            $table->timestamps();

            $table->primary(['idDetalle', 'idPedido']);
            $table->foreign('idCocinero')->references('idEmpleado')->on('empleado');
            $table->foreign(['idDetalle', 'idPedido'])->references(['idDetalle', 'idPedido'])->on('detalle_pedido')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Primero eliminar la tabla que depende
        Schema::dropIfExists('detalle_preparacion');
        Schema::dropIfExists('preparacion_plato');
    }
};
