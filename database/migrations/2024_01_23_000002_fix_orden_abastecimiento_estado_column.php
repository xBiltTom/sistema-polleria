<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Cambiar el tipo de estado de INT a VARCHAR
        DB::statement('ALTER TABLE `orden_abastecimiento` MODIFY COLUMN `estado` VARCHAR(50) DEFAULT "pendiente"');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // No revertimos a INT para evitar errores con datos existentes
        DB::statement('ALTER TABLE `orden_abastecimiento` MODIFY COLUMN `estado` VARCHAR(50) DEFAULT "pendiente"');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
