<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaInsumo extends Model
{
    protected $table = 'categoria_insumo';
    protected $primaryKey = 'idCategoria';
    public $timestamps = false;

    protected $fillable = [
        'nombreCategoria',
        'descripcionCategoria'
    ];

    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'idCategoria');
    }
}
