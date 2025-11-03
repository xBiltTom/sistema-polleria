<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->integer('idProducto')->primary()->autoIncrement();
            $table->string('descripcion', 100)->nullable();
            $table->string('nombre', 45)->nullable();
            $table->integer('stock')->nullable();
            $table->decimal('precioVenta', 10, 2)->nullable();
            $table->date('fechaVencimiento')->nullable();
            $table->integer('idCategoriaProducto');
            $table->timestamps();

            $table->foreign('idCategoriaProducto')
                ->references('idCategoriaProducto')
                ->on('categoria_producto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
