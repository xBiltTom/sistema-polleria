<?php

namespace App\Livewire\Admin;

use App\Models\CategoriaProducto;
use Livewire\Component;
use Livewire\Attributes\On;

class CategoriaProductoForm extends Component
{
    public $categorias = [];
    public $showForm = false;
    public $editingId = null;

    public $nombre = '';
    public $descripcion = '';

    protected $rules = [
        'nombre' => 'required|string|max:45',
        'descripcion' => 'nullable|string|max:100',
    ];

    #[On('categoriaProductoGuardada')]
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
        $this->categorias = CategoriaProducto::all()->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $categoria = CategoriaProducto::find($this->editingId);
            $categoria->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
            ]);
            $this->dispatch('notify', 'Categoría actualizada exitosamente');
        } else {
            CategoriaProducto::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
            ]);
            $this->dispatch('notify', 'Categoría creada exitosamente');
        }

        $this->loadCategorias();
        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('categoriaProductoGuardada');
    }

    public function edit($id)
    {
        $categoria = CategoriaProducto::find($id);
        $this->editingId = $id;
        $this->nombre = $categoria->nombre;
        $this->descripcion = $categoria->descripcion;
        $this->showForm = true;
    }

    public function delete($id)
    {
        CategoriaProducto::find($id)->delete();
        $this->loadCategorias();
        $this->dispatch('notify', 'Categoría eliminada');
    }

    public function render()
    {
        return view('livewire.admin.categoria-producto-form');
    }
}
