<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    use HasFactory;

    protected $table = 'categoria_producto';
    protected $primaryKey = 'idCategoriaProducto';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'idCategoriaProducto', 'idCategoriaProducto');
    }
}
