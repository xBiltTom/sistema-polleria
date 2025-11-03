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
        
        // Modificar columnas de proveedor
        DB::statement('ALTER TABLE `proveedor` MODIFY COLUMN `razonSocial` VARCHAR(100)');
        DB::statement('ALTER TABLE `proveedor` MODIFY COLUMN `ruc` VARCHAR(15)');
        DB::statement('ALTER TABLE `proveedor` MODIFY COLUMN `telefono` VARCHAR(15)');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::statement('ALTER TABLE `proveedor` MODIFY COLUMN `razonSocial` INT');
        DB::statement('ALTER TABLE `proveedor` MODIFY COLUMN `ruc` INT');
        DB::statement('ALTER TABLE `proveedor` MODIFY COLUMN `telefono` INT');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
