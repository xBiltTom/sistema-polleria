<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->integer('idPago');
            $table->integer('idPagoOrden');
            $table->integer('nroOperacion')->nullable();
            $table->date('fechaPago')->nullable();

            $table->primary(['idPago', 'idPagoOrden']);
            $table->foreign('idPagoOrden')->references('idPagoOrden')->on('pago_orden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
