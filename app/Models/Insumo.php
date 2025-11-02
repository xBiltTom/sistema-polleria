<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'insumo';
    protected $primaryKey = 'idInsumo';
    public $timestamps = false;

    protected $fillable = [
        'nombreInsumo',
        'stock',
        'precioInsumo',
        'fechaVencimiento',
        'estado',
        'idCategoria'
    ];

    protected $casts = [
        'precioInsumo' => 'decimal:2',
        'fechaVencimiento' => 'date'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaInsumo::class, 'idCategoria');
    }

    public function unidad()
    {
        return $this->hasOne(UnidadInsumo::class, 'idInsumo');
    }

    public function addInsumos()
    {
        return $this->hasMany(AddInsumo::class, 'idInsumo');
    }

    public function detallesPreparacion()
    {
        return $this->hasMany(DetallePreparacion::class, 'idInsumo');
    }
}
