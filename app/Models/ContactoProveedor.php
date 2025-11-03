<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoProveedor extends Model
{
    use HasFactory;

    protected $table = 'contacto_proveedor';
    protected $primaryKey = 'idContactoProveedor';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'idContactoProveedor',
        'nombroContacto',
        'apellidoContacto',
        'dniContacto',
        'emailContacto',
        'cargoContacto',
        'telefonoContacto',
    ];

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class, 'idContactoProveedor', 'idContactoProveedor');
    }
}
