<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $primaryKey = ['idPago', 'idPagoOrden'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idPagoOrden',
        'nroOperacion',
        'fechaPago'
    ];

    protected $casts = [
        'fechaPago' => 'date'
    ];

    public function pagoOrden()
    {
        return $this->belongsTo(PagoOrden::class, 'idPagoOrden');
    }
}
