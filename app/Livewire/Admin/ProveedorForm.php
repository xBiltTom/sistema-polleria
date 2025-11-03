<?php

namespace App\Livewire\Admin;

use App\Models\Proveedor;
use App\Models\ContactoProveedor;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProveedorForm extends Component
{
    public $proveedores = [];
    public $showForm = false;
    public $editingId = null;

    public $razonSocial = '';
    public $ruc = '';
    public $direccion = '';
    public $telefono = '';
    public $email = '';
    public $nroCuenta = '';

    // Datos del contacto
    public $nombroContacto = '';
    public $apellidoContacto = '';
    public $dniContacto = '';
    public $emailContacto = '';
    public $cargoContacto = '';
    public $telefonoContacto = '';

    protected $rules = [
        'razonSocial' => 'required|string|max:100',
        'ruc' => 'required|string|max:15',
        'direccion' => 'nullable|string|max:100',
        'telefono' => 'nullable|string|max:15',
        'email' => 'nullable|email|max:70',
        'nroCuenta' => 'nullable|string|max:45',
        'nombroContacto' => 'nullable|string|max:30',
        'apellidoContacto' => 'nullable|string|max:50',
        'dniContacto' => 'nullable|string|max:9',
        'emailContacto' => 'nullable|email|max:70',
        'cargoContacto' => 'nullable|string|max:20',
        'telefonoContacto' => 'nullable|string|max:9',
    ];

    public function mount()
    {
        $this->loadProveedores();
    }

    public function loadProveedores()
    {
        $this->proveedores = Proveedor::with('contacto')
            ->orderBy('idProveedor', 'DESC')
            ->get()
            ->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->razonSocial = '';
        $this->ruc = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->email = '';
        $this->nroCuenta = '';
        $this->nombroContacto = '';
        $this->apellidoContacto = '';
        $this->dniContacto = '';
        $this->emailContacto = '';
        $this->cargoContacto = '';
        $this->telefonoContacto = '';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate($this->rules);

        try {
            DB::beginTransaction();

            if ($this->editingId) {
                $proveedor = Proveedor::find($this->editingId);
                
                // Actualizar contacto si existe
                if ($proveedor->idContactoProveedor) {
                    $contacto = ContactoProveedor::find($proveedor->idContactoProveedor);
                    $contacto->update([
                        'nombroContacto' => $this->nombroContacto,
                        'apellidoContacto' => $this->apellidoContacto,
                        'dniContacto' => $this->dniContacto,
                        'emailContacto' => $this->emailContacto,
                        'cargoContacto' => $this->cargoContacto,
                        'telefonoContacto' => $this->telefonoContacto,
                    ]);
                }

                // Actualizar proveedor
                $proveedor->update([
                    'razonSocial' => $this->razonSocial,
                    'ruc' => $this->ruc,
                    'direccion' => $this->direccion,
                    'telefono' => $this->telefono,
                    'emial' => $this->email,
                    'nroCuenta' => $this->nroCuenta,
                ]);

                $this->dispatch('notify', 'Proveedor actualizado exitosamente');
            } else {
                // Obtener IDs disponibles
                $nextContactoId = (DB::table('contacto_proveedor')->max('idContactoProveedor') ?? 0) + 1;
                $nextProveedorId = (DB::table('proveedor')->max('idProveedor') ?? 0) + 1;
                
                // Crear contacto
                ContactoProveedor::create([
                    'idContactoProveedor' => $nextContactoId,
                    'nombroContacto' => $this->nombroContacto,
                    'apellidoContacto' => $this->apellidoContacto,
                    'dniContacto' => $this->dniContacto,
                    'emailContacto' => $this->emailContacto,
                    'cargoContacto' => $this->cargoContacto,
                    'telefonoContacto' => $this->telefonoContacto,
                ]);

                // Crear proveedor
                Proveedor::create([
                    'idProveedor' => $nextProveedorId,
                    'idContactoProveedor' => $nextContactoId,
                    'razonSocial' => $this->razonSocial,
                    'ruc' => $this->ruc,
                    'direccion' => $this->direccion,
                    'telefono' => $this->telefono,
                    'emial' => $this->email,
                    'nroCuenta' => $this->nroCuenta,
                ]);

                $this->dispatch('notify', 'Proveedor creado exitosamente');
            }

            DB::commit();
            $this->loadProveedores();
            $this->showForm = false;
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', 'Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function edit($id)
    {
        $proveedor = Proveedor::with('contacto')->find($id);
        $this->editingId = $id;
        $this->razonSocial = $proveedor->razonSocial;
        $this->ruc = $proveedor->ruc;
        $this->direccion = $proveedor->direccion;
        $this->telefono = $proveedor->telefono;
        $this->email = $proveedor->emial;
        $this->nroCuenta = $proveedor->nroCuenta;

        if ($proveedor->contacto) {
            $this->nombroContacto = $proveedor->contacto->nombroContacto;
            $this->apellidoContacto = $proveedor->contacto->apellidoContacto;
            $this->dniContacto = $proveedor->contacto->dniContacto;
            $this->emailContacto = $proveedor->contacto->emailContacto;
            $this->cargoContacto = $proveedor->contacto->cargoContacto;
            $this->telefonoContacto = $proveedor->contacto->telefonoContacto;
        }

        $this->showForm = true;
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            
            $proveedor = Proveedor::find($id);
            
            if ($proveedor && $proveedor->idContactoProveedor) {
                ContactoProveedor::find($proveedor->idContactoProveedor)?->delete();
            }
            
            $proveedor?->delete();
            
            DB::commit();
            $this->loadProveedores();
            $this->dispatch('notify', 'Proveedor eliminado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.proveedor-form');
    }
}
