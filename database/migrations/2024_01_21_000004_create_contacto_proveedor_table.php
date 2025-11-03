<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacto_proveedor', function (Blueprint $table) {
            $table->integer('idContactoProveedor')->primary();
            $table->string('nombroContacto', 30)->nullable();
            $table->string('apellidoContacto', 50)->nullable();
            $table->string('dniContacto', 9)->nullable();
            $table->string('emailContacto', 70)->nullable();
            $table->string('cargoContacto', 20)->nullable();
            $table->string('telefonoContacto', 9)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacto_proveedor');
    }
};
