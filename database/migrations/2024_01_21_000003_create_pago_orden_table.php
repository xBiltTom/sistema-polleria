<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_orden', function (Blueprint $table) {
            $table->integer('idPagoOrden')->primary();
            $table->float('montoTotal')->nullable();
            $table->integer('estado')->nullable();
            $table->integer('idTipoPago');
            $table->timestamps();

            $table->foreign('idTipoPago')->references('idTipoPago')->on('tipo_pago_orden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_orden');
    }
};
