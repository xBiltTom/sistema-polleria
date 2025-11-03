<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado', function (Blueprint $table) {
            $table->integer('idEmpleado')->primary();
            $table->char('dniEmpleado', 8);
            $table->string('nombreEmpleado', 45);
            $table->string('apellidoEmpleado', 60);
            $table->char('nroCelular', 9);
            $table->boolean('estado')->nullable();
            $table->integer('idHorario');
            $table->unsignedBigInteger('idUsuario')->nullable();
            $table->integer('idTipoEmpleado');
            $table->timestamps();

            $table->foreign('idHorario')->references('idHorario')->on('horario');
            $table->foreign('idUsuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idTipoEmpleado')->references('idTipoEmpleado')->on('tipo_empleado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado');
    }
};
