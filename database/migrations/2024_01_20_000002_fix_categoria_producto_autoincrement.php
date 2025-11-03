<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si la tabla existe antes de modificarla
        if (Schema::hasTable('categoria_producto')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('ALTER TABLE `categoria_producto` MODIFY COLUMN `idCategoriaProducto` INT NOT NULL AUTO_INCREMENT');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categoria_producto')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('ALTER TABLE `categoria_producto` MODIFY COLUMN `idCategoriaProducto` INT NOT NULL');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
};
