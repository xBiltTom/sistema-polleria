<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;

    protected $fillable = [
        'idPedido',
        'precioUnitario',
        'cantidad',
        'descripcion',
        'estado',
        'observacion',
        'idPlato',
        'idProducto',
    ];

    protected $casts = [
        'precioUnitario' => 'decimal:2'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'idPedido');
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class, 'idPlato');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto');
    }

    public function preparacion()
    {
        return $this->hasOne(PreparacionPlato::class, 'idDetalle');
    }
}
