<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoOrden extends Model
{
    protected $table = 'pago_orden';
    protected $primaryKey = 'idPagoOrden';
    public $timestamps = false;

    protected $fillable = [
        'montoTotal',
        'estado',
        'idTipoPago'
    ];

    protected $casts = [
        'montoTotal' => 'float'
    ];

    public function tipoPago()
    {
        return $this->belongsTo(TipoPagoOrden::class, 'idTipoPago');
    }

    public function ordenesAbastecimiento()
    {
        return $this->hasMany(OrdenAbastecimiento::class, 'idPagoOrden');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'idPagoOrden');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'idPagoOrden');
    }
}
