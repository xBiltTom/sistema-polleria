<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenAbastecimiento extends Model
{
    use HasFactory;

    protected $table = 'orden_abastecimiento';
    protected $primaryKey = 'idOrden';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'idOrden',
        'idPagoOrden',
        'idProveedor',
        'idEmpleado',
        'descripcion',
        'fechaOrden',
        'estado',
        'fechaEntrega',
    ];

    protected $casts = [
        'fechaOrden' => 'date',
        'fechaEntrega' => 'date',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor', 'idProveedor');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'idEmpleado', 'idEmpleado');
    }

    public function addInsumos()
    {
        return $this->hasMany(AddInsumo::class, 'idOrden', 'idOrden');
    }

    public function addProductos()
    {
        return $this->hasMany(AddProducto::class, 'idOrden', 'idOrden');
    }
}
