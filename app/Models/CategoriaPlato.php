<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaPlato extends Model
{
    use HasFactory;

    protected $table = 'categoria_plato';
    protected $primaryKey = 'idCategoriaPlato';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'descripción',
    ];

    public function platos()
    {
        return $this->hasMany(Plato::class, 'idCategoria', 'idCategoriaPlato');
    }
}
