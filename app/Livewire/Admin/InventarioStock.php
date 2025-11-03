<?php

namespace App\Livewire\Admin;

use App\Models\Producto;
use App\Models\Insumo;
use Livewire\Component;
use Livewire\WithPagination;

class InventarioStock extends Component
{
    use WithPagination;

    public $buscar = '';
    public $tipo = 'productos';
    public $ordenarPor = 'stock'; // stock, nombre

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
                ->orderBy($this->ordenarPor, 'asc')
                ->paginate(15);
        } else {
            $insumos = Insumo::query()
                ->when($this->buscar, function ($q) {
                    $q->where('nombreInsumo', 'like', '%' . $this->buscar . '%');
                })
                ->orderBy($this->ordenarPor, 'asc')
                ->paginate(15);
        }

        return view('livewire.admin.inventario-stock', [
            'productos' => $productos,
            'insumos' => $insumos,
        ]);
    }
}
