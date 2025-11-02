<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuota';
    protected $primaryKey = 'idCuota';
    public $timestamps = false;

    protected $fillable = [
        'idPagoOrden',
        'fechaVencimineto',
        'montoPagar',
        'saldoPendiente',
        'estado'
    ];

    protected $casts = [
        'fechaVencimineto' => 'date',
        'montoPagar' => 'float',
        'saldoPendiente' => 'float'
    ];

    public function pagoOrden()
    {
        return $this->belongsTo(PagoOrden::class, 'idPagoOrden');
    }
}
