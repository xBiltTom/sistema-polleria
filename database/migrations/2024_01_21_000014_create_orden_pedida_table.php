<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_pedida', function (Blueprint $table) {
            $table->integer('idPedidoOrden');
            $table->integer('idOrden');
            $table->string('nombrePedidoOrden', 45)->nullable();
            $table->integer('cantidadPedidoOrden')->nullable();

            $table->primary(['idPedidoOrden', 'idOrden']);
            $table->foreign('idOrden')->references('idOrden')->on('orden_abastecimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_pedida');
    }
};
