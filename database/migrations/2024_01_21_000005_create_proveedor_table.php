<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedor', function (Blueprint $table) {
            $table->integer('idProveedor')->primary();
            $table->integer('idContactoProveedor');
            $table->integer('razonSocial')->nullable();
            $table->integer('ruc')->nullable();
            $table->string('direccion', 100)->nullable();
            $table->integer('telefono')->nullable();
            $table->string('emial', 70)->nullable();
            $table->string('nroCuenta', 45)->nullable();
            $table->timestamps();

            $table->foreign('idContactoProveedor')->references('idContactoProveedor')->on('contacto_proveedor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedor');
    }
};
