<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('add_insumo', function (Blueprint $table) {
            $table->integer('idDetalleOpAddInsumo');
            $table->integer('idOrden');
            $table->integer('idInsumo');
            $table->float('precio')->nullable();
            $table->integer('cantidad')->nullable();
            $table->dateTime('timestamps')->nullable();

            $table->primary(['idDetalleOpAddInsumo', 'idOrden']);
            $table->foreign('idOrden')->references('idOrden')->on('orden_abastecimiento');
            $table->foreign('idInsumo')->references('idInsumo')->on('insumo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_insumo');
    }
};
