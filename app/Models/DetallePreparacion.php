<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePreparacion extends Model
{
    protected $table = 'detalle_preparacion';
    protected $primaryKey = 'idDetallePreparacion';
    public $timestamps = false;

    protected $fillable = [
        'idDetalle',
        'idPedido',
        'idInsumo',
        'precioInsumo'
    ];

    protected $casts = [
        'precioInsumo' => 'decimal:2'
    ];

    public function preparacionPlato()
    {
        return $this->belongsTo(PreparacionPlato::class, ['idDetalle', 'idPedido']);
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'idInsumo');
    }
}
