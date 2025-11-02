<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horario';
    protected $primaryKey = 'idHorario';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'horaEntrada',
        'horaSalida'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'idHorario');
    }
}
