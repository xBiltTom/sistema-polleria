<?php

namespace App\Livewire\Admin;

use App\Models\Mesa;
use Livewire\Component;

class MesaForm extends Component
{
    public $mesas = [];
    public $showForm = false;
    public $editingId = null;

    public $nroMesa = '';
    public $capacidadMesa = '';
    public $descripcionMesa = '';
    public $estado = 'disponible';

    protected $rules = [
        'nroMesa' => 'required|integer|min:1',
        'capacidadMesa' => 'required|integer|min:1|max:20',
        'descripcionMesa' => 'required|string|max:30',
        'estado' => 'required|in:disponible,ocupada,mantenimiento',
    ];

    public function mount()
    {
        $this->loadMesas();
    }

    public function loadMesas()
    {
        $this->mesas = Mesa::orderBy('nroMesa')->get()->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nroMesa = '';
        $this->capacidadMesa = '';
        $this->descripcionMesa = '';
        $this->estado = 'disponible';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                $mesa = Mesa::find($this->editingId);
                $mesa->update([
                    'nroMesa' => $this->nroMesa,
                    'capacidadMesa' => $this->capacidadMesa,
                    'descripcionMesa' => $this->descripcionMesa,
                    'estado' => $this->estado,
                ]);
                $this->dispatch('notify', 'Mesa actualizada exitosamente');
            } else {
                Mesa::create([
                    'nroMesa' => $this->nroMesa,
                    'capacidadMesa' => $this->capacidadMesa,
                    'descripcionMesa' => $this->descripcionMesa,
                    'estado' => $this->estado,
                ]);
                $this->dispatch('notify', 'Mesa creada exitosamente');
            }

            $this->loadMesas();
            $this->showForm = false;
            $this->resetForm();
        } catch (\Exception $e) {
            dd($e);
            $this->dispatch('notify', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $mesa = Mesa::find($id);
        $this->editingId = $id;
        $this->nroMesa = $mesa->nroMesa;
        $this->capacidadMesa = $mesa->capacidadMesa;
        $this->descripcionMesa = $mesa->descripcionMesa;
        $this->estado = $mesa->estado ?? 'disponible';
        $this->showForm = true;
    }

    public function delete($id)
    {
        try {
            Mesa::find($id)->delete();
            $this->loadMesas();
            $this->dispatch('notify', 'Mesa eliminada');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.mesa-form');
    }
}
