<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'idCliente';
    public $timestamps = false;

    protected $fillable = [
        'dniCliente',
        'nombreCliente',
        'apellidoCliente',
        'celularCliente',
        'direccion',
        'email',
        'idTipoCliente',
        'rucCliente',
        'razonSocial',
    ];

    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'idTipoCliente', 'idTipo');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idCliente', 'idCliente');
    }
}
