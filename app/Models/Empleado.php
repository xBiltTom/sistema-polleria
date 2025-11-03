<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleado';
    protected $primaryKey = 'idEmpleado';
    public $timestamps = false;

    protected $fillable = [
        'idEmpleado',
        'dniEmpleado',
        'nombreEmpleado',
        'apellidoEmpleado',
        'nroCelular',
        'estado',
        'idHorario',
        'idUsuario',
        'idTipoEmpleado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function tipoEmpleado()
    {
        return $this->belongsTo(TipoEmpleado::class, 'idTipoEmpleado', 'idTipoEmpleado');
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'idHorario', 'idHorario');
    }
}
