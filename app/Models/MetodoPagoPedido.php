<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPagoPedido extends Model
{
    protected $table = 'metodo_pago_pedido';
    protected $primaryKey = 'idMetodoPago';
    public $timestamps = false;

    protected $fillable = ['descripcion'];

    public function pagosPedidos()
    {
        return $this->hasMany(PagosPedidos::class, 'idMetodoPago');
    }
}
