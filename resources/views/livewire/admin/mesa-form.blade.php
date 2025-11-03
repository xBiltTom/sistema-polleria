<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Gestión de Mesas</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nueva Mesa
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Mesa</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Número de Mesa *</label>
                                    <input type="number" class="form-control" wire:model="nroMesa" placeholder="1">
                                    @error('nroMesa') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Capacidad *</label>
                                    <input type="number" class="form-control" wire:model="capacidadMesa" placeholder="4" min="1" max="20">
                                    @error('capacidadMesa') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Estado *</label>
                                    <select class="form-control" wire:model="estado">
                                        <option value="disponible">Disponible</option>
                                        <option value="ocupada">Ocupada</option>
                                        <option value="mantenimiento">Mantenimiento</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Descripción *</label>
                            <input type="text" class="form-control" wire:model="descripcionMesa" placeholder="Ej: Junto a la ventana">
                            @error('descripcionMesa') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex gap-2">
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
                            <th>Número</th>
                            <th>Capacidad</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mesas as $mesa)
                            <tr>
                                <td>{{ $mesa['idMesa'] }}</td>
                                <td><strong>Mesa {{ $mesa['nroMesa'] }}</strong></td>
                                <td>{{ $mesa['capacidadMesa'] }} personas</td>
                                <td>{{ $mesa['descripcionMesa'] }}</td>
                                <td>
                                    <span class="badge {{ $mesa['estado'] === 'disponible' ? 'bg-success' : ($mesa['estado'] === 'ocupada' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($mesa['estado']) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $mesa['idMesa'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $mesa['idMesa'] }})">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay mesas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
