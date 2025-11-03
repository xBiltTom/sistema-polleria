<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idProducto';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precioVenta',
        'stock',
        'idCategoriaProducto',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'idCategoriaProducto', 'idCategoriaProducto');
    }
}
