<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $table = 'insumo';
    protected $primaryKey = 'idInsumo';
    public $timestamps = false;

    protected $fillable = [
        'nombreInsumo',
        'stock',
        'precioInsumo',
        'fechaVencimiento',
        'estado',
        'idCategoria',
    ];

    protected $casts = [
        'fechaVencimiento' => 'date',
        'precioInsumo' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaInsumo::class, 'idCategoria', 'idCategoria');
    }

    public function unidades()
    {
        return $this->hasMany(UnidadInsumo::class, 'idInsumo', 'idInsumo');
    }
}
