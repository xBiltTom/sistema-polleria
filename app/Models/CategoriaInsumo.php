<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaInsumo extends Model
{
    use HasFactory;

    protected $table = 'categoria_insumo';
    protected $primaryKey = 'idCategoria';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'nombreCategoria',
        'descripcionCategoria',
    ];

    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'idCategoria', 'idCategoria');
    }
}
