<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesa';
    protected $primaryKey = 'idMesa';
    public $timestamps = false;

    protected $fillable = [
        'nroMesa',
        'capacidadMesa',
        'descripcionMesa'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idMesa');
    }
}
