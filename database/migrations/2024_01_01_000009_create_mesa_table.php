<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesa', function (Blueprint $table) {
            $table->integer('idMesa')->primary()->autoIncrement();
            $table->integer('nroMesa');
            $table->integer('capacidadMesa');
            $table->string('descripcionMesa', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesa');
    }
};
