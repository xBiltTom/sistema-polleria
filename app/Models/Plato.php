<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $table = 'plato';
    protected $primaryKey = 'idPlato';
    public $timestamps = false;

    protected $fillable = [
        'nombrePlato',
        'descripcion',
        'precioVenta',
        'estado',
        'stock',
        'urlImagen',
        'idCategoria'
    ];

    protected $casts = [
        'precioVenta' => 'decimal:2',
        'estado' => 'boolean'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaPlato::class, 'idCategoria', 'idCategoriaPlato');
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'idPlato');
    }
}
