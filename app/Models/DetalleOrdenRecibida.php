<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOrdenRecibida extends Model
{
    protected $table = 'detalle_orden_recibida';
    protected $primaryKey = ['idExistencia', 'idOrden'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idOrden',
        'nombreExistencia',
        'cantidadExistencia',
        'precioExistencia',
        'IGV'
    ];

    protected $casts = [
        'precioExistencia' => 'float',
        'IGV' => 'decimal:2'
    ];

    public function ordenRecibida()
    {
        return $this->belongsTo(OrdenRecibida::class, 'idOrden');
    }
}
