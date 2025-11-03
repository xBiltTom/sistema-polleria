<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->integer('idCliente')->primary()->autoIncrement();
            $table->char('dniCliente', 8)->nullable();
            $table->string('nombreCliente', 45)->nullable();
            $table->string('apellidoCliente', 45)->nullable();
            $table->char('celularCliente', 9)->nullable();
            $table->string('direccion', 45)->nullable();
            $table->string('email', 45)->nullable();
            $table->integer('idTipoCliente');
            $table->char('rucCliente', 11)->nullable();
            $table->string('razonSocial', 45)->nullable();
            $table->timestamps();

            $table->foreign('idTipoCliente')->references('idTipo')->on('tipo_cliente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
