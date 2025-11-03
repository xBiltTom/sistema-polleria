<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_abastecimiento', function (Blueprint $table) {
            $table->integer('idOrden')->primary();
            $table->integer('idPagoOrden');
            $table->integer('idProveedor');
            $table->integer('idEmpleado');
            $table->string('descripcion', 100)->nullable();
            $table->date('fechaOrden')->nullable();
            $table->integer('estado')->nullable();
            $table->date('fechaEntrega')->nullable();
            $table->timestamps();

            $table->foreign('idPagoOrden')->references('idPagoOrden')->on('pago_orden');
            $table->foreign('idProveedor')->references('idProveedor')->on('proveedor');
            $table->foreign('idEmpleado')->references('idEmpleado')->on('empleado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_abastecimiento');
    }
};
