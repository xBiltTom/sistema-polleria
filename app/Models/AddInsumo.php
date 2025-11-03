<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddInsumo extends Model
{
    use HasFactory;

    protected $table = 'add_insumo';
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = ['idDetalleOpAddInsumo', 'idOrden'];

    protected $fillable = [
        'idDetalleOpAddInsumo',
        'idOrden',
        'idInsumo',
        'precio',
        'cantidad',
        'timestamps',
    ];

    public function orden()
    {
        return $this->belongsTo(OrdenAbastecimiento::class, 'idOrden', 'idOrden');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'idInsumo', 'idInsumo');
    }
}
