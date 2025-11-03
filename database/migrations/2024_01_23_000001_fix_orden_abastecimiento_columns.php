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
        
        // Intentar eliminar la foreign key si existe
        try {
            DB::statement('ALTER TABLE `orden_abastecimiento` DROP FOREIGN KEY `fk_ORDEN_ABASTECIMIENTO_PAGO_ORDEN1`');
        } catch (\Exception $e) {
            // La clave foránea no existe, ignorar
        }

        // Hacer que idPagoOrden sea nullable
        DB::statement('ALTER TABLE `orden_abastecimiento` MODIFY COLUMN `idPagoOrden` INT NULL');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::statement('ALTER TABLE `orden_abastecimiento` MODIFY COLUMN `idPagoOrden` INT');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
