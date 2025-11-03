<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddProducto extends Model
{
    use HasFactory;

    protected $table = 'add_producto';
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = ['idDetalleOpAddProducto', 'idOrden'];

    protected $fillable = [
        'idDetalleOpAddProducto',
        'idOrden',
        'idProducto',
        'cantidad',
        'precio',
        'timestamps',
    ];

    public function orden()
    {
        return $this->belongsTo(OrdenAbastecimiento::class, 'idOrden', 'idOrden');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }
}
