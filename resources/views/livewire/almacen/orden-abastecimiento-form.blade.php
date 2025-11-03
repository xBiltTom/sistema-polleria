<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Órdenes de Abastecimiento</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nueva Orden
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Orden de Abastecimiento</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Proveedor *</label>
                                    <select class="form-control" wire:model="idProveedor">
                                        <option value="">Seleccionar proveedor...</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor['idProveedor'] }}">
                                                {{ $proveedor['razonSocial'] }} (RUC: {{ $proveedor['ruc'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idProveedor') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Fecha de Orden *</label>
                                    <input type="date" class="form-control" wire:model="fechaOrden">
                                    @error('fechaOrden') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Estado *</label>
                                    <select class="form-control" wire:model="estado">
                                        <option value="pendiente">Pendiente</option>
                                        <option value="en_proceso">En Proceso</option>
                                        <option value="completada">Completada</option>
                                        <option value="cancelada">Cancelada</option>
                                    </select>
                                    @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Descripción</label>
                            <textarea class="form-control" wire:model="descripcion" rows="3" 
                                      placeholder="Describe los detalles de la orden..."></textarea>
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
                            <th>Proveedor</th>
                            <th>Descripción</th>
                            <th>Fecha Orden</th>
                            <th>Fecha Entrega</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordenes as $orden)
                            <tr>
                                <td><strong>#{{ $orden['idOrden'] }}</strong></td>
                                <td>{{ $orden['proveedor']['razonSocial'] ?? 'N/A' }}</td>
                                <td>{{ $orden['descripcion'] ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($orden['fechaOrden'])->format('d/m/Y') }}</td>
                                <td>
                                    @if($orden['fechaEntrega'])
                                        {{ \Carbon\Carbon::parse($orden['fechaEntrega'])->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        {{ $orden['estado'] === 'pendiente' ? 'bg-warning' : 
                                           ($orden['estado'] === 'en_proceso' ? 'bg-info' : 
                                           ($orden['estado'] === 'completada' ? 'bg-success' : 'bg-danger')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $orden['estado'])) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $orden['idOrden'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" 
                                            onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $orden['idOrden'] }})">🗑️
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay órdenes registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
