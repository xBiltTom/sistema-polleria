<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadInsumo extends Model
{
    protected $table = 'unidad_insumo';
    protected $primaryKey = 'idUnidad';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'idInsumo'
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'idInsumo');
    }
}
