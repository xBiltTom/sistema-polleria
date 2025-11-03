<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->integer('idDetalle')->primary()->autoIncrement();
            $table->integer('idPedido');
            $table->decimal('precioUnitario', 10, 2);
            $table->integer('cantidad');
            $table->string('descripcion', 45)->nullable();
            $table->string('estado', 30)->nullable();
            $table->string('observacion', 45)->nullable();
            $table->integer('idPlato');
            $table->timestamps();

            $table->foreign('idPedido')->references('idPedido')->on('pedido')->onDelete('cascade');
            $table->foreign('idPlato')->references('idPlato')->on('plato');
            $table->unique(['idDetalle', 'idPedido']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
