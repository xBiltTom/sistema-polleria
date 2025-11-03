<?php

namespace App\Livewire\Almacen;

use App\Models\OrdenAbastecimiento;
use App\Models\Proveedor;
use App\Models\Empleado;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class OrdenAbastecimientoForm extends Component
{
    public $ordenes = [];
    public $proveedores = [];
    public $empleados = [];
    public $showForm = false;
    public $editingId = null;

    public $idProveedor = '';
    public $descripcion = '';
    public $fechaOrden = '';
    public $estado = 'pendiente';

    protected $rules = [
        'idProveedor' => 'required|integer',
        'descripcion' => 'nullable|string|max:100',
        'fechaOrden' => 'required|date',
        'estado' => 'required|in:pendiente,en_proceso,completada,cancelada',
    ];

    public function mount()
    {
        $this->loadOrdenes();
        $this->loadProveedores();
        $this->loadEmpleados();
        $this->fechaOrden = now()->format('Y-m-d');
    }

    public function loadOrdenes()
    {
        try {
            $this->ordenes = OrdenAbastecimiento::with('proveedor', 'empleado')
                ->orderBy('idOrden', 'DESC')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            $this->ordenes = [];
        }
    }

    public function loadProveedores()
    {
        try {
            $this->proveedores = Proveedor::all()->toArray();
        } catch (\Exception $e) {
            $this->proveedores = [];
        }
    }

    public function loadEmpleados()
    {
        try {
            $this->empleados = Empleado::where('idTipoEmpleado', 4)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            $this->empleados = [];
        }
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->idProveedor = '';
        $this->descripcion = '';
        $this->fechaOrden = now()->format('Y-m-d');
        $this->estado = 'pendiente';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                $orden = OrdenAbastecimiento::find($this->editingId);
                if (!$orden) {
                    $this->dispatch('notify', 'Orden no encontrada');
                    return;
                }

                $orden->update([
                    'idProveedor' => (int) $this->idProveedor,
                    'descripcion' => $this->descripcion,
                    'fechaOrden' => $this->fechaOrden,
                    'estado' => $this->estado,
                ]);
                $this->dispatch('notify', 'Orden actualizada exitosamente');
            } else {
                $empleadoActual = auth()->user()->empleado;
                
                if (!$empleadoActual) {
                    $this->dispatch('notify', 'No tienes perfil de empleado');
                    return;
                }

                $nextId = (int) (DB::table('orden_abastecimiento')->max('idOrden') ?? 0) + 1;

                OrdenAbastecimiento::create([
                    'idOrden' => $nextId,
                    'idProveedor' => (int) $this->idProveedor,
                    'idEmpleado' => $empleadoActual->idEmpleado,
                    'descripcion' => $this->descripcion,
                    'fechaOrden' => $this->fechaOrden,
                    'estado' => $this->estado,
                    'idPagoOrden' => null,
                ]);

                $this->dispatch('notify', 'Orden creada exitosamente');
            }

            $this->loadOrdenes();
            $this->showForm = false;
            $this->resetForm();
        } catch (\Exception $e) {
            \Log::error('Error guardando orden: ' . $e->getMessage());
            $this->dispatch('notify', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $orden = OrdenAbastecimiento::find($id);
            if (!$orden) {
                $this->dispatch('notify', 'Orden no encontrada');
                return;
            }

            $this->editingId = $id;
            $this->idProveedor = $orden->idProveedor;
            $this->descripcion = $orden->descripcion;
            $this->fechaOrden = \Carbon\Carbon::parse($orden->fechaOrden)->format('Y-m-d');
            $this->estado = $orden->estado;
            $this->showForm = true;
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Error al cargar orden: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $orden = OrdenAbastecimiento::find($id);
            if (!$orden) {
                $this->dispatch('notify', 'Orden no encontrada');
                return;
            }

            $orden->delete();
            $this->loadOrdenes();
            $this->dispatch('notify', 'Orden eliminada');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.almacen.orden-abastecimiento-form');
    }
}
