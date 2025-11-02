<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenAbastecimiento extends Model
{
    protected $table = 'orden_abastecimiento';
    protected $primaryKey = 'idOrden';
    public $timestamps = false;

    protected $fillable = [
        'idPagoOrden',
        'idProveedor',
        'idEmpleado',
        'descripcion',
        'fechaOrden',
        'estado',
        'fechaEntrega'
    ];

    protected $casts = [
        'fechaOrden' => 'date',
        'fechaEntrega' => 'date'
    ];

    public function pagoOrden()
    {
        return $this->belongsTo(PagoOrden::class, 'idPagoOrden');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado');
    }

    public function addInsumos()
    {
        return $this->hasMany(AddInsumo::class, 'idOrden');
    }

    public function addProductos()
    {
        return $this->hasMany(AddProducto::class, 'idOrden');
    }

    public function ordenRecibida()
    {
        return $this->hasOne(OrdenRecibida::class, 'idOrden');
    }

    public function ordenesPedidas()
    {
        return $this->hasMany(OrdenPedida::class, 'idOrden');
    }
}
