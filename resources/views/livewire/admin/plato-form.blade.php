<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Platos</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nuevo Plato
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Plato</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nombre Plato *</label>
                                    <input type="text" class="form-control" wire:model="nombrePlato" placeholder="Ej: Pollo a la brasa">
                                    @error('nombrePlato') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Categoría *</label>
                                    <select class="form-control" wire:model="idCategoria">
                                        <option value="">Seleccionar...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat['idCategoriaPlato'] }}">{{ $cat['descripción'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('idCategoria') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Descripción *</label>
                            <textarea class="form-control" wire:model="descripcion" rows="2" placeholder="Descripción del plato..."></textarea>
                            @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Precio Venta *</label>
                                    <input type="number" step="0.01" class="form-control" wire:model="precioVenta" placeholder="0.00">
                                    @error('precioVenta') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Estado *</label>
                                    <select class="form-control" wire:model="estado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
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
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($platos as $plato)
                            <tr>
                                <td>{{ $plato['idPlato'] }}</td>
                                <td>{{ $plato['nombrePlato'] }}</td>
                                <td>{{ $plato['categoria']['descripción'] ?? '-' }}</td>
                                <td>{{ $plato['descripcion'] }}</td>
                                <td>S/. {{ number_format($plato['precioVenta'], 2) }}</td>
                                <td>
                                    <span class="badge {{ $plato['estado'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $plato['estado'] ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $plato['idPlato'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $plato['idPlato'] }})">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay platos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
