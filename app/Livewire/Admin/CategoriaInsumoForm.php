<?php

namespace App\Livewire\Admin;

use App\Models\CategoriaInsumo;
use Livewire\Component;

class CategoriaInsumoForm extends Component
{
    public $categorias = [];
    public $showForm = false;
    public $editingId = null;

    public $nombreCategoria = '';
    public $descripcionCategoria = '';

    protected $rules = [
        'nombreCategoria' => 'required|string|max:45',
        'descripcionCategoria' => 'nullable|string|max:100',
    ];

    #[On('categoriaGuardada')]
    public function recargarCategorias()
    {
        $this->loadCategorias();
    }

    public function mount()
    {
        $this->loadCategorias();
    }

    public function loadCategorias()
    {
        $this->categorias = CategoriaInsumo::all()->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nombreCategoria = '';
        $this->descripcionCategoria = '';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $categoria = CategoriaInsumo::find($this->editingId);
            $categoria->update([
                'nombreCategoria' => $this->nombreCategoria,
                'descripcionCategoria' => $this->descripcionCategoria,
            ]);
            $this->dispatch('notify', 'Categoría actualizada exitosamente');
        } else {
            CategoriaInsumo::create([
                'nombreCategoria' => $this->nombreCategoria,
                'descripcionCategoria' => $this->descripcionCategoria,
            ]);
            $this->dispatch('notify', 'Categoría creada exitosamente');
        }

        $this->loadCategorias();
        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('categoriaGuardada');
    }

    public function edit($id)
    {
        $categoria = CategoriaInsumo::find($id);
        $this->editingId = $id;
        $this->nombreCategoria = $categoria->nombreCategoria;
        $this->descripcionCategoria = $categoria->descripcionCategoria;
        $this->showForm = true;
    }

    public function delete($id)
    {
        CategoriaInsumo::find($id)->delete();
        $this->loadCategorias();
        $this->dispatch('notify', 'Categoría eliminada');
    }

    public function render()
    {
        return view('livewire.admin.categoria-insumo-form');
    }
}
