<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_insumo', function (Blueprint $table) {
            $table->integer('idCategoria')->primary();
            $table->string('nombreCategoria', 45)->nullable();
            $table->string('descripcionCategoria', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_insumo');
    }
};
