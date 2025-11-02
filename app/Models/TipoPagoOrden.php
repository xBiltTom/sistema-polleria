<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPagoOrden extends Model
{
    protected $table = 'tipo_pago_orden';
    protected $primaryKey = 'idTipoPago';
    public $timestamps = false;

    protected $fillable = ['descripcion'];

    public function pagosOrden()
    {
        return $this->hasMany(PagoOrden::class, 'idTipoPago');
    }
}
