<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insumo', function (Blueprint $table) {
            // Asegurarse que existe el campo precio
            if (!Schema::hasColumn('insumo', 'precio')) {
                $table->decimal('precio', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('insumo', function (Blueprint $table) {
            if (Schema::hasColumn('insumo', 'precio')) {
                $table->dropColumn('precio');
            }
        });
    }
};
