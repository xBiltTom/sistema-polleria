<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'idProducto';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'nombre',
        'stock',
        'precioVenta',
        'fechaVencimiento',
        'idCategoriaProducto',
    ];

    protected $casts = [
        'fechaVencimiento' => 'date',
        'precioVenta' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'idCategoriaProducto', 'idCategoriaProducto');
    }
}
