<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoProveedor extends Model
{
    protected $table = 'contacto_proveedor';
    protected $primaryKey = 'idContactoProveedor';
    public $timestamps = false;

    protected $fillable = [
        'nombroContacto',
        'apellidoContacto',
        'dniContacto',
        'emailContacto',
        'cargoContacto',
        'telefonoContacto'
    ];

    public function proveedor()
    {
        return $this->hasOne(Proveedor::class, 'idContactoProveedor');
    }
}
