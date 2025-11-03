<?php

namespace App\Livewire\Almacen;

use App\Models\Producto;
use App\Models\Insumo;
use Livewire\Component;
use Livewire\WithPagination;

class InventarioStock extends Component
{
    use WithPagination;

    public $buscar = '';
    public $tipo = 'productos'; // productos o insumos

    public function updatedBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $productos = collect();
        $insumos = collect();

        if ($this->tipo === 'productos') {
            $productos = Producto::query()
                ->when($this->buscar, function ($q) {
                    $q->where('nombre', 'like', '%' . $this->buscar . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->buscar . '%');
                })
                ->orderBy('stock', 'asc')
                ->paginate(10);
        } else {
            $insumos = Insumo::query()
                ->when($this->buscar, function ($q) {
                    $q->where('nombreInsumo', 'like', '%' . $this->buscar . '%');
                })
                ->orderBy('stock', 'asc')
                ->paginate(10);
        }

        return view('livewire.almacen.inventario-stock', [
            'productos' => $productos,
            'insumos' => $insumos,
        ]);
    }
}
