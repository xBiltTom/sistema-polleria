<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenPedida extends Model
{
    protected $table = 'orden_pedida';
    protected $primaryKey = ['idPedidoOrden', 'idOrden'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idOrden',
        'nombrePedidoOrden',
        'cantidadPedidoOrden'
    ];

    public function ordenAbastecimiento()
    {
        return $this->belongsTo(OrdenAbastecimiento::class, 'idOrden');
    }
}
