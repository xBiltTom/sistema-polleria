<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

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
        'idMozo',
    ];

    protected $casts = [
        'fechaPedido' => 'datetime',
    ];

    public function tipoPedido()
    {
        return $this->belongsTo(TipoPedido::class, 'idTipoPedido', 'idTipoPedido');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'idMesa', 'idMesa');
    }

    public function mozo()
    {
        return $this->belongsTo(Empleado::class, 'idMozo', 'idEmpleado');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'idPedido', 'idPedido');
    }
}
