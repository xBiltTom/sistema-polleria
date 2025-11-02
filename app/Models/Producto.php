<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idProducto';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'nombre',
        'stock',
        'precioVenta',
        'fechaVencimiento',
        'idCategoriaProducto'
    ];

    protected $casts = [
        'precioVenta' => 'float',
        'fechaVencimiento' => 'date'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'idCategoriaProducto');
    }

    public function addProductos()
    {
        return $this->hasMany(AddProducto::class, 'idProducto');
    }

    public function agregadosPedido()
    {
        return $this->hasMany(AgregadoPedido::class, 'idProducto');
    }
}
