<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Insumos</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nuevo Insumo
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Insumo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nombre Insumo *</label>
                                    <input type="text" class="form-control" wire:model="nombreInsumo" placeholder="Ej: Pollo">
                                    @error('nombreInsumo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Categoría *</label>
                                    <select class="form-control" wire:model="idCategoria">
                                        <option value="">Seleccionar...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat['idCategoria'] }}">{{ $cat['nombreCategoria'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('idCategoria') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Precio Unitario *</label>
                                    <input type="number" step="0.01" class="form-control" wire:model="precioInsumo" placeholder="0.00">
                                    @error('precioInsumo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Estado *</label>
                                    <select class="form-control" wire:model="estado">
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
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
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($insumos as $insumo)
                            <tr>
                                <td>{{ $insumo['idInsumo'] }}</td>
                                <td>{{ $insumo['nombreInsumo'] }}</td>
                                <td>{{ $insumo['categoria']['nombreCategoria'] ?? '-' }}</td>
                                <td>S/. {{ number_format($insumo['precioInsumo'], 2) }}</td>
                                <td>
                                    <span class="badge {{ $insumo['estado'] == 'activo' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $insumo['estado'] }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $insumo['idInsumo'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $insumo['idInsumo'] }})">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay insumos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
