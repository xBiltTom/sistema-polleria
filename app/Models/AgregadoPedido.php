<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgregadoPedido extends Model
{
    protected $table = 'agregado_pedido';
    protected $primaryKey = 'idAgregado';
    public $timestamps = false;

    protected $fillable = [
        'idPedido',
        'cantidad',
        'precioUnitario',
        'idProducto'
    ];

    protected $casts = [
        'precioUnitario' => 'float'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto');
    }
}
