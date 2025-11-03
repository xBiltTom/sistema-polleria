<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesa';
    protected $primaryKey = 'idMesa';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'nroMesa',
        'capacidadMesa',
        'descripcionMesa',
        'estado',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idMesa', 'idMesa');
    }
}
