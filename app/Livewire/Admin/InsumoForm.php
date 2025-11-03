<?php

namespace App\Livewire\Admin;

use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use Livewire\Component;
use Livewire\Attributes\On;

class InsumoForm extends Component
{
    public $insumos = [];
    public $categorias = [];
    public $showForm = false;
    public $editingId = null;

    public $nombreInsumo = '';
    public $idCategoria = '';
    public $precioInsumo = '';
    public $estado = 'activo';

    protected $rules = [
        'nombreInsumo' => 'required|string|max:45',
        'idCategoria' => 'required|exists:categoria_insumo,idCategoria',
        'precioInsumo' => 'required|numeric|min:0',
        'estado' => 'required|in:activo,inactivo',
    ];

    #[On('categoriaGuardada')]
    public function recargarCategorias()
    {
        $this->categorias = CategoriaInsumo::all()->toArray();
    }

    public function mount()
    {
        $this->loadInsumos();
        $this->categorias = CategoriaInsumo::all()->toArray();
    }

    public function loadInsumos()
    {
        $this->insumos = Insumo::with('categoria')->get()->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nombreInsumo = '';
        $this->idCategoria = '';
        $this->precioInsumo = '';
        $this->estado = 'activo';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $insumo = Insumo::find($this->editingId);
            $insumo->update([
                'nombreInsumo' => $this->nombreInsumo,
                'idCategoria' => $this->idCategoria,
                'precioInsumo' => $this->precioInsumo,
                'estado' => $this->estado,
            ]);
            $this->dispatch('notify', 'Insumo actualizado exitosamente');
        } else {
            Insumo::create([
                'nombreInsumo' => $this->nombreInsumo,
                'idCategoria' => $this->idCategoria,
                'precioInsumo' => $this->precioInsumo,
                'estado' => $this->estado,
                'stock' => 0,
            ]);
            $this->dispatch('notify', 'Insumo creado exitosamente');
        }

        $this->loadInsumos();
        $this->showForm = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $insumo = Insumo::find($id);
        $this->editingId = $id;
        $this->nombreInsumo = $insumo->nombreInsumo;
        $this->idCategoria = $insumo->idCategoria;
        $this->precioInsumo = $insumo->precioInsumo;
        $this->estado = $insumo->estado;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Insumo::find($id)->delete();
        $this->loadInsumos();
        $this->dispatch('notify', 'Insumo eliminado');
    }

    public function render()
    {
        return view('livewire.admin.insumo-form');
    }
}
