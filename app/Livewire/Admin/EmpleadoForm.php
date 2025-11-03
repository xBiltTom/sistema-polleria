<?php

namespace App\Livewire\Admin;

use App\Models\Empleado;
use App\Models\User;
use App\Models\TipoEmpleado;
use App\Models\Horario;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmpleadoForm extends Component
{
    public $empleados = [];
    public $tiposEmpleado = [];
    public $horarios = [];
    public $showForm = false;
    public $editingId = null;

    public $dniEmpleado = '';
    public $nombreEmpleado = '';
    public $apellidoEmpleado = '';
    public $nroCelular = '';
    public $idHorario = '';
    public $idTipoEmpleado = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $crearUsuario = false;

    protected function rules()
    {
        $rules = [
            'dniEmpleado' => 'required|string|size:8',
            'nombreEmpleado' => 'required|string|max:45',
            'apellidoEmpleado' => 'required|string|max:60',
            'nroCelular' => 'required|string|size:9',
            'idHorario' => 'required|exists:horario,idHorario',
            'idTipoEmpleado' => 'required|exists:tipo_empleado,idTipoEmpleado',
        ];

        if ($this->crearUsuario) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }

    public function mount()
    {
        $this->loadEmpleados();
        $this->tiposEmpleado = TipoEmpleado::all()->toArray();
        $this->horarios = Horario::all()->toArray();
    }

    public function loadEmpleados()
    {
        $this->empleados = Empleado::with('tipoEmpleado', 'horario', 'usuario')
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
        $this->dniEmpleado = '';
        $this->nombreEmpleado = '';
        $this->apellidoEmpleado = '';
        $this->nroCelular = '';
        $this->idHorario = '';
        $this->idTipoEmpleado = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->crearUsuario = false;
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate($this->rules());

        try {
            if ($this->editingId) {
                $empleado = Empleado::find($this->editingId);
                $empleado->update([
                    'dniEmpleado' => $this->dniEmpleado,
                    'nombreEmpleado' => $this->nombreEmpleado,
                    'apellidoEmpleado' => $this->apellidoEmpleado,
                    'nroCelular' => $this->nroCelular,
                    'idHorario' => $this->idHorario,
                    'idTipoEmpleado' => $this->idTipoEmpleado,
                ]);
                $this->dispatch('notify', 'Empleado actualizado exitosamente');
            } else {
                // Obtener el próximo ID disponible
                $nextId = DB::table('empleado')->max('idEmpleado') + 1;

                $empleadoData = [
                    'idEmpleado' => $nextId,
                    'dniEmpleado' => $this->dniEmpleado,
                    'nombreEmpleado' => $this->nombreEmpleado,
                    'apellidoEmpleado' => $this->apellidoEmpleado,
                    'nroCelular' => $this->nroCelular,
                    'idHorario' => $this->idHorario,
                    'idTipoEmpleado' => $this->idTipoEmpleado,
                    'estado' => 1,
                ];

                // Si se marca crear usuario
                if ($this->crearUsuario && $this->email && $this->password) {
                    $user = User::create([
                        'name' => trim($this->nombreEmpleado . ' ' . $this->apellidoEmpleado),
                        'email' => $this->email,
                        'password' => Hash::make($this->password),
                        'email_verified_at' => now(),
                    ]);
                    $empleadoData['idUsuario'] = $user->id;
                    $this->dispatch('notify', 'Usuario creado exitosamente');
                }

                Empleado::create($empleadoData);
                $this->dispatch('notify', 'Empleado creado exitosamente');
            }

            $this->loadEmpleados();
            $this->showForm = false;
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $empleado = Empleado::find($id);
        $this->editingId = $id;
        $this->dniEmpleado = $empleado->dniEmpleado;
        $this->nombreEmpleado = $empleado->nombreEmpleado;
        $this->apellidoEmpleado = $empleado->apellidoEmpleado;
        $this->nroCelular = $empleado->nroCelular;
        $this->idHorario = $empleado->idHorario;
        $this->idTipoEmpleado = $empleado->idTipoEmpleado;
        $this->email = $empleado->usuario->email ?? '';
        $this->crearUsuario = $empleado->idUsuario ? true : false;
        $this->showForm = true;
    }

    public function delete($id)
    {
        try {
            $empleado = Empleado::find($id);
            if ($empleado->idUsuario) {
                User::find($empleado->idUsuario)->delete();
            }
            $empleado->delete();
            $this->loadEmpleados();
            $this->dispatch('notify', 'Empleado eliminado');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.empleado-form');
    }
}
