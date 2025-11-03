<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    use HasFactory;

    protected $table = 'tipo_cliente';
    protected $primaryKey = 'idTipo';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'idTipoCliente', 'idTipo');
    }
}
