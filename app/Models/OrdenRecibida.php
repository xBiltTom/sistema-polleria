<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenRecibida extends Model
{
    protected $table = 'orden_recibida';
    protected $primaryKey = 'idOrden';
    public $timestamps = false;

    protected $fillable = [
        'nroFactura',
        'observaciones'
    ];

    public function ordenAbastecimiento()
    {
        return $this->belongsTo(OrdenAbastecimiento::class, 'idOrden');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOrdenRecibida::class, 'idOrden');
    }
}
