<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Categorías de Productos</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nueva Categoría
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Categoría</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Nombre Categoría *</label>
                            <input type="text" class="form-control" wire:model="nombre" placeholder="Ej: Bebidas">
                            @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Descripción</label>
                            <textarea class="form-control" wire:model="descripcion" rows="3" placeholder="Descripción..."></textarea>
                            @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $cat)
                            <tr>
                                <td>{{ $cat['idCategoriaProducto'] }}</td>
                                <td>{{ $cat['nombre'] }}</td>
                                <td>{{ $cat['descripcion'] ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $cat['idCategoriaProducto'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $cat['idCategoriaProducto'] }})">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay categorías registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
