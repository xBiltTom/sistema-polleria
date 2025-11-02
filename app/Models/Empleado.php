<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'idEmpleado';
    public $timestamps = false;

    protected $fillable = [
        'dniEmpleado',
        'nombreEmpleado',
        'apellidoEmpleado',
        'nroCelular',
        'estado',
        'idHorario',
        'idUsuario'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'idHorario');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

    public function ordenesAbastecimiento()
    {
        return $this->hasMany(OrdenAbastecimiento::class, 'idEmpleado');
    }

    public function pedidosComoMozo()
    {
        return $this->hasMany(Pedido::class, 'idMozo');
    }

    public function preparacionesPlato()
    {
        return $this->hasMany(PreparacionPlato::class, 'idCocinero');
    }
}
