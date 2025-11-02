<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddInsumo extends Model
{
    protected $table = 'add_insumo';
    protected $primaryKey = 'idDetalleOpAddInsumo';
    public $timestamps = true;
    const CREATED_AT = 'timestamps';
    const UPDATED_AT = null;

    protected $fillable = [
        'idOrden',
        'idInsumo',
        'precio',
        'cantidad'
    ];

    protected $casts = [
        'precio' => 'float',
        'timestamps' => 'datetime'
    ];

    public function orden()
    {
        return $this->belongsTo(OrdenAbastecimiento::class, 'idOrden');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'idInsumo');
    }
}
