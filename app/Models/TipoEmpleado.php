<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEmpleado extends Model
{
    protected $table = 'tipo_empleado';
    protected $primaryKey = 'idTipoEmpleado';
    public $timestamps = false;
    protected $fillable = ['descripcion'];

    public function empleados(){
        return $this->hasMany(Empleado::class,'idTipoEmpleado');
    }
}
