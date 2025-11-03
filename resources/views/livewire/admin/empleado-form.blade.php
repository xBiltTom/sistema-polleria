<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Gestión de Empleados</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nuevo Empleado
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Empleado</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>DNI *</label>
                                    <input type="text" class="form-control" wire:model="dniEmpleado" placeholder="12345678" maxlength="8">
                                    @error('dniEmpleado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nombre *</label>
                                    <input type="text" class="form-control" wire:model="nombreEmpleado" placeholder="Juan">
                                    @error('nombreEmpleado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Apellido *</label>
                                    <input type="text" class="form-control" wire:model="apellidoEmpleado" placeholder="Perez">
                                    @error('apellidoEmpleado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Celular *</label>
                                    <input type="text" class="form-control" wire:model="nroCelular" placeholder="987654321" maxlength="9">
                                    @error('nroCelular') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Horario *</label>
                                    <select class="form-control" wire:model="idHorario">
                                        <option value="">Seleccionar...</option>
                                        @foreach($horarios as $horario)
                                            <option value="{{ $horario['idHorario'] }}">{{ $horario['descripcion'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('idHorario') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tipo de Empleado *</label>
                                    <select class="form-control" wire:model="idTipoEmpleado">
                                        <option value="">Seleccionar...</option>
                                        @foreach($tiposEmpleado as $tipo)
                                            <option value="{{ $tipo['idTipoEmpleado'] }}">{{ $tipo['descripcion'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('idTipoEmpleado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card card-info mt-3">
                            <div class="card-header">
                                <h6>
                                    <input type="checkbox" wire:model.live="crearUsuario"> Crear Usuario para acceso al sistema
                                </h6>
                            </div>
                            @if($crearUsuario)
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Correo *</label>
                                                <input type="email" class="form-control" wire:model="email" placeholder="correo@example.com">
                                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Contraseña *</label>
                                                <input type="password" class="form-control" wire:model="password" placeholder="Mínimo 6 caracteres">
                                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Confirmar Contraseña *</label>
                                        <input type="password" class="form-control" wire:model="password_confirmation" placeholder="Repite la contraseña">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-success btn-sm" wire:click="save">💾 Guardar</button>
                            <button class="btn btn-secondary btn-sm" wire:click="resetForm; $set('showForm', false)">❌ Cancelar</button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Celular</th>
                            <th>Tipo</th>
                            <th>Horario</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleados as $emp)
                            <tr>
                                <td>{{ $emp['idEmpleado'] }}</td>
                                <td>{{ $emp['dniEmpleado'] }}</td>
                                <td>{{ $emp['nombreEmpleado'] }}</td>
                                <td>{{ $emp['apellidoEmpleado'] }}</td>
                                <td>{{ $emp['nroCelular'] }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $emp['tipoEmpleado']['descripcion'] ?? '-' }}</span>
                                </td>
                                <td>{{ $emp['horario']['descripcion'] ?? '-' }}</td>
                                <td>
                                    @if($emp['usuario'])
                                        <span class="badge bg-success">{{ $emp['usuario']['email'] }}</span>
                                    @else
                                        <span class="badge bg-secondary">Sin usuario</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $emp['idEmpleado'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $emp['idEmpleado'] }})">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No hay empleados registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
