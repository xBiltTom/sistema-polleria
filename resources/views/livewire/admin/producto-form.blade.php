<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Productos</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nuevo Producto
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Producto</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nombre Producto *</label>
                                    <input type="text" class="form-control" wire:model="nombre" placeholder="Ej: Coca Cola">
                                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Categoría *</label>
                                    <select class="form-control" wire:model="idCategoriaProducto">
                                        <option value="">Seleccionar...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat['idCategoriaProducto'] }}">{{ $cat['nombre'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('idCategoriaProducto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Descripción</label>
                            <textarea class="form-control" wire:model="descripcion" rows="2" placeholder="Descripción del producto..."></textarea>
                            @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Precio Venta *</label>
                            <input type="number" step="0.01" class="form-control" wire:model="precioVenta" placeholder="0.00">
                            @error('precioVenta') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>{{ $producto['idProducto'] }}</td>
                                <td>{{ $producto['nombre'] }}</td>
                                <td>{{ $producto['categoria']['nombre'] ?? '-' }}</td>
                                <td>{{ $producto['descripcion'] ?? '-' }}</td>
                                <td>S/. {{ number_format($producto['precioVenta'], 2) }}</td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $producto['idProducto'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $producto['idProducto'] }})">🗑️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay productos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
