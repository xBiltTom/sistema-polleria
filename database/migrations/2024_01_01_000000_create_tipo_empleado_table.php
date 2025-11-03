<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_empleado', function (Blueprint $table) {
            $table->integer('idTipoEmpleado')->primary();
            $table->string('descripcion', 45);
            $table->timestamps();
        });

        DB::table('tipo_empleado')->insert([
            ['idTipoEmpleado' => 1, 'descripcion' => 'admin'],
            ['idTipoEmpleado' => 2, 'descripcion' => 'mozo'],
            ['idTipoEmpleado' => 3, 'descripcion' => 'cocinero'],
            ['idTipoEmpleado' => 4, 'descripcion' => 'jefe_almacen'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_empleado');
    }
};
