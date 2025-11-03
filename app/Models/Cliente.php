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

    protected static function booted()
    {
        static::creating(function ($cliente) {
            // Si no hay tipo de cliente, asignar tipo 1 (cliente general)
            if (!$cliente->idTipoCliente) {
                $cliente->idTipoCliente = 1;
            }
        });
    }

    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'idTipoCliente', 'idTipo');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idCliente', 'idCliente');
    }
}
