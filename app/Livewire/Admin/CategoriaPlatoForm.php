<?php

namespace App\Livewire\Admin;

use App\Models\CategoriaPlato;
use Livewire\Component;
use Livewire\Attributes\On;

class CategoriaPlatoForm extends Component
{
    public $categorias = [];
    public $showForm = false;
    public $editingId = null;

    public $descripcion = '';

    protected $rules = [
        'descripcion' => 'required|string|max:100',
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
        $this->categorias = CategoriaPlato::all()->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->descripcion = '';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $categoria = CategoriaPlato::find($this->editingId);
            $categoria->update([
                'descripción' => $this->descripcion,
            ]);
            $this->dispatch('notify', 'Categoría actualizada exitosamente');
        } else {
            CategoriaPlato::create([
                'descripción' => $this->descripcion,
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
        $categoria = CategoriaPlato::find($id);
        $this->editingId = $id;
        $this->descripcion = $categoria->descripción;
        $this->showForm = true;
    }

    public function delete($id)
    {
        CategoriaPlato::find($id)->delete();
        $this->loadCategorias();
        $this->dispatch('notify', 'Categoría eliminada');
    }

    public function render()
    {
        return view('livewire.admin.categoria-plato-form');
    }
}
