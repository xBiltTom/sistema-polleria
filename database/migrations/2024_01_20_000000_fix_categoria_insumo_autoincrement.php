<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Desabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Modificar la tabla para que idCategoria sea AUTO_INCREMENT
        DB::statement('ALTER TABLE `categoria_insumo` MODIFY COLUMN `idCategoria` INT NOT NULL AUTO_INCREMENT');
        
        // Reabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('ALTER TABLE `categoria_insumo` MODIFY COLUMN `idCategoria` INT NOT NULL');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
