<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plato', function (Blueprint $table) {
            $table->integer('idPlato')->primary()->autoIncrement();
            $table->string('nombrePlato', 20);
            $table->string('descripcion', 45);
            $table->decimal('precioVenta', 10, 2);
            $table->boolean('estado')->nullable();
            $table->integer('stock');
            $table->text('urlImagen')->nullable();
            $table->integer('idCategoria');
            $table->timestamps();

            $table->foreign('idCategoria')->references('idCategoriaPlato')->on('categoria_plato');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plato');
    }
};
