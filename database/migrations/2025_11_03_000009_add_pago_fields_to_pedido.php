<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            if (!Schema::hasColumn('pedido', 'estadoPago')) {
                $table->string('estadoPago', 20)->default('pendiente')->after('estadoPedido');
            }
            if (!Schema::hasColumn('pedido', 'totalPedido')) {
                $table->decimal('totalPedido', 10, 2)->default(0)->after('estadoPago');
            }
            if (!Schema::hasColumn('pedido', 'fechaPago')) {
                $table->dateTime('fechaPago')->nullable()->after('totalPedido');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            if (Schema::hasColumn('pedido', 'estadoPago')) {
                $table->dropColumn('estadoPago');
            }
            if (Schema::hasColumn('pedido', 'totalPedido')) {
                $table->dropColumn('totalPedido');
            }
            if (Schema::hasColumn('pedido', 'fechaPago')) {
                $table->dropColumn('fechaPago');
            }
        });
    }
};
