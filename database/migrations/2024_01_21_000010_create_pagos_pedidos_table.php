<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_pedidos', function (Blueprint $table) {
            $table->integer('idPago')->primary()->autoIncrement();
            $table->decimal('monto', 10, 2);
            $table->char('nroOperacion', 8);
            $table->integer('idPedido');
            $table->integer('idMetodoPago');
            $table->timestamps();

            $table->foreign('idPedido')->references('idPedido')->on('pedido');
            $table->foreign('idMetodoPago')->references('idMetodoPago')->on('metodo_pago_pedido');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_pedidos');
    }
};
