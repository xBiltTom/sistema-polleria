<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_recibida', function (Blueprint $table) {
            $table->integer('idOrden')->primary();
            $table->integer('nroFactura')->nullable();
            $table->string('observaciones', 100)->nullable();
            $table->timestamps();

            $table->foreign('idOrden')->references('idOrden')->on('orden_abastecimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_recibida');
    }
};
