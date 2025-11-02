<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparacionPlato extends Model
{
    protected $table = 'preparacion_plato';
    protected $primaryKey = ['idDetalle', 'idPedido'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idDetalle',
        'idPedido',
        'estado',
        'idCocinero'
    ];

    public function detallePedido()
    {
        return $this->belongsTo(DetallePedido::class, 'idDetalle');
    }

    public function cocinero()
    {
        return $this->belongsTo(Empleado::class, 'idCocinero');
    }

    public function detallesPreparacion()
    {
        return $this->hasMany(DetallePreparacion::class, ['idDetalle', 'idPedido']);
    }
}
