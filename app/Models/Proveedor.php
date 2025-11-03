<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedor';
    protected $primaryKey = 'idProveedor';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'idProveedor',
        'idContactoProveedor',
        'razonSocial',
        'ruc',
        'direccion',
        'telefono',
        'emial',
        'nroCuenta',
    ];

    public function contacto()
    {
        return $this->belongsTo(ContactoProveedor::class, 'idContactoProveedor', 'idContactoProveedor');
    }

    public function ordenesAbastecimiento()
    {
        return $this->hasMany(OrdenAbastecimiento::class, 'idProveedor', 'idProveedor');
    }
}
