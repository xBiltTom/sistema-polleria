<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'idPedido';
    public $timestamps = false;

    protected $fillable = [
        'fechaPedido',
        'estadoPedido',
        'direccionEntrega',
        'descripcionPedido',
        'idTipoPedido',
        'idCliente',
        'idMesa',
        'idMozo'
    ];

    protected $casts = [
        'fechaPedido' => 'datetime'
    ];

    public function tipoPedido()
    {
        return $this->belongsTo(TipoPedido::class, 'idTipoPedido');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'idMesa');
    }

    public function mozo()
    {
        return $this->belongsTo(Empleado::class, 'idMozo');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'idPedido');
    }

    public function agregados()
    {
        return $this->hasMany(AgregadoPedido::class, 'idPedido');
    }

    public function pagosPedidos()
    {
        return $this->hasMany(PagosPedidos::class, 'idPedido');
    }
}
