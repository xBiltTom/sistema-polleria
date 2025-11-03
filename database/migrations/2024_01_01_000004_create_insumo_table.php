<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumo', function (Blueprint $table) {
            $table->integer('idInsumo')->primary()->autoIncrement();
            $table->string('nombreInsumo', 45);
            $table->integer('stock')->nullable();
            $table->decimal('precioInsumo', 10, 2)->nullable();
            $table->date('fechaVencimiento')->nullable();
            $table->string('estado', 45)->nullable();
            $table->integer('idCategoria');
            $table->timestamps();

            $table->foreign('idCategoria')->references('idCategoria')->on('categoria_insumo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumo');
    }
};
