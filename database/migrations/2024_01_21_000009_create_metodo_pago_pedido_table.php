<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodo_pago_pedido', function (Blueprint $table) {
            $table->integer('idMetodoPago')->primary()->autoIncrement();
            $table->string('descripcion', 45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodo_pago_pedido');
    }
};
