<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuota', function (Blueprint $table) {
            $table->integer('idCuota');
            $table->integer('idPagoOrden');
            $table->date('fechaVencimineto')->nullable();
            $table->float('montoPagar')->nullable();
            $table->float('saldoPendiente')->nullable();
            $table->integer('estado')->nullable();

            $table->primary(['idCuota', 'idPagoOrden']);
            $table->foreign('idPagoOrden')->references('idPagoOrden')->on('pago_orden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuota');
    }
};
