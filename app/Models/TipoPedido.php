<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPedido extends Model
{
    use HasFactory;

    protected $table = 'tipo_pedido';
    protected $primaryKey = 'idTipoPedido';
    public $timestamps = false;

    protected $fillable = [
        'desscripcion',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idTipoPedido', 'idTipoPedido');
    }
}
