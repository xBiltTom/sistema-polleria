<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'idProveedor';
    public $timestamps = false;

    protected $fillable = [
        'idContactoProveedor',
        'razonSocial',
        'ruc',
        'direccion',
        'telefono',
        'emial',
        'nroCuenta'
    ];

    public function contacto()
    {
        return $this->belongsTo(ContactoProveedor::class, 'idContactoProveedor');
    }

    public function ordenesAbastecimiento()
    {
        return $this->hasMany(OrdenAbastecimiento::class, 'idProveedor');
    }
}
