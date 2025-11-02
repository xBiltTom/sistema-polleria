<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddProducto extends Model
{
    protected $table = 'add_producto';
    protected $primaryKey = 'idDetalleOpAddProducto';
    public $timestamps = true;
    const CREATED_AT = 'timestamps';
    const UPDATED_AT = null;

    protected $fillable = [
        'idOrden',
        'idProducto',
        'cantidad',
        'precio'
    ];

    protected $casts = [
        'precio' => 'float',
        'timestamps' => 'datetime'
    ];

    public function orden()
    {
        return $this->belongsTo(OrdenAbastecimiento::class, 'idOrden');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto');
    }
}
