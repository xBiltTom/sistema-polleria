<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagosPedidos extends Model
{
    protected $table = 'pagos_pedidos';
    protected $primaryKey = 'idPago';
    public $timestamps = false;

    protected $fillable = [
        'monto',
        'nroOperacion',
        'idPedido',
        'idMetodoPago'
    ];

    protected $casts = [
        'monto' => 'decimal:2'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPagoPedido::class, 'idMetodoPago');
    }
}
