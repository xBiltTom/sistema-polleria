<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->integer('idPedido')->primary()->autoIncrement();
            $table->dateTime('fechaPedido');
            $table->string('estadoPedido', 20)->nullable();
            $table->string('direccionEntrega', 60)->nullable();
            $table->string('descripcionPedido', 60)->nullable();
            $table->integer('idTipoPedido');
            $table->integer('idCliente');
            $table->integer('idMesa');
            $table->integer('idMozo');
            $table->timestamps();

            $table->foreign('idTipoPedido')->references('idTipoPedido')->on('tipo_pedido');
            $table->foreign('idCliente')->references('idCliente')->on('cliente');
            $table->foreign('idMesa')->references('idMesa')->on('mesa');
            $table->foreign('idMozo')->references('idEmpleado')->on('empleado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
