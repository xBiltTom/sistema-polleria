<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaPlato extends Model
{
    protected $table = 'categoria_plato';
    protected $primaryKey = 'idCategoriaPlato';
    public $timestamps = false;

    protected $fillable = ['descripción'];

    public function platos()
    {
        return $this->hasMany(Plato::class, 'idCategoria');
    }
}
