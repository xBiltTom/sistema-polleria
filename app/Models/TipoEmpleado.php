<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEmpleado extends Model
{
    use HasFactory;

    protected $table = 'tipo_empleado';
    protected $primaryKey = 'idTipoEmpleado';
    public $timestamps = false;

    protected $fillable = [
        'idTipoEmpleado',
        'descripcion',
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'idTipoEmpleado', 'idTipoEmpleado');
    }
}
