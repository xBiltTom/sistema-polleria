<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preparacion_plato', function (Blueprint $table) {
            // Agregar las columnas si no existen
            if (!Schema::hasColumn('preparacion_plato', 'fechaInicio')) {
                $table->timestamp('fechaInicio')->nullable()->after('idCocinero');
            }
            if (!Schema::hasColumn('preparacion_plato', 'fechaFin')) {
                $table->timestamp('fechaFin')->nullable()->after('fechaInicio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('preparacion_plato', function (Blueprint $table) {
            if (Schema::hasColumn('preparacion_plato', 'fechaInicio')) {
                $table->dropColumn('fechaInicio');
            }
            if (Schema::hasColumn('preparacion_plato', 'fechaFin')) {
                $table->dropColumn('fechaFin');
            }
        });
    }
};
