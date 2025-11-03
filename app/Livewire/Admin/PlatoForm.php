<?php

namespace App\Livewire\Admin;

use App\Models\Plato;
use App\Models\CategoriaPlato;
use Livewire\Component;
use Livewire\Attributes\On;

class PlatoForm extends Component
{
    public $platos = [];
    public $categorias = [];
    public $showForm = false;
    public $editingId = null;

    public $nombrePlato = '';
    public $descripcion = '';
    public $precioVenta = '';
    public $idCategoria = '';
    public $estado = 1;

    protected $rules = [
        'nombrePlato' => 'required|string|max:20',
        'descripcion' => 'required|string|max:45',
        'precioVenta' => 'required|numeric|min:0',
        'idCategoria' => 'required|exists:categoria_plato,idCategoriaPlato',
        'estado' => 'required|boolean',
    ];

    #[On('categoriaGuardada')]
    public function recargarCategorias()
    {
        $this->categorias = CategoriaPlato::all()->toArray();
    }

    public function mount()
    {
        $this->loadPlatos();
        $this->categorias = CategoriaPlato::all()->toArray();
    }

    public function loadPlatos()
    {
        $this->platos = Plato::with('categoria')->get()->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nombrePlato = '';
        $this->descripcion = '';
        $this->precioVenta = '';
        $this->idCategoria = '';
        $this->estado = 1;
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $plato = Plato::find($this->editingId);
            $plato->update([
                'nombrePlato' => $this->nombrePlato,
                'descripcion' => $this->descripcion,
                'precioVenta' => $this->precioVenta,
                'idCategoria' => $this->idCategoria,
                'estado' => $this->estado,
            ]);
            $this->dispatch('notify', 'Plato actualizado exitosamente');
        } else {
            Plato::create([
                'nombrePlato' => $this->nombrePlato,
                'descripcion' => $this->descripcion,
                'precioVenta' => $this->precioVenta,
                'idCategoria' => $this->idCategoria,
                'estado' => $this->estado,
                'stock' => 0,
            ]);
            $this->dispatch('notify', 'Plato creado exitosamente');
        }

        $this->loadPlatos();
        $this->showForm = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $plato = Plato::find($id);
        $this->editingId = $id;
        $this->nombrePlato = $plato->nombrePlato;
        $this->descripcion = $plato->descripcion;
        $this->precioVenta = $plato->precioVenta;
        $this->idCategoria = $plato->idCategoria;
        $this->estado = $plato->estado;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Plato::find($id)->delete();
        $this->loadPlatos();
        $this->dispatch('notify', 'Plato eliminado');
    }

    public function render()
    {
        return view('livewire.admin.plato-form');
    }
}
