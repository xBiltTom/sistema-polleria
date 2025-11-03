<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadInsumo extends Model
{
    use HasFactory;

    protected $table = 'unidad_insumo';
    protected $primaryKey = 'idUnidad';
    public $timestamps = false;

    protected $fillable = [
        'idUnidad',
        'descripcion',
        'idInsumo',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'idInsumo', 'idInsumo');
    }
}
