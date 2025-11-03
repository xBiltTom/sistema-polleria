<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_orden_recibida', function (Blueprint $table) {
            $table->integer('idExistencia');
            $table->integer('idOrden');
            $table->string('nombreExistencia', 45)->nullable();
            $table->integer('cantidadExistencia')->nullable();
            $table->float('precioExistencia')->nullable();
            $table->decimal('IGV', 10, 0)->nullable();

            $table->primary(['idExistencia', 'idOrden']);
            $table->foreign('idOrden')->references('idOrden')->on('orden_recibida');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_orden_recibida');
    }
};
