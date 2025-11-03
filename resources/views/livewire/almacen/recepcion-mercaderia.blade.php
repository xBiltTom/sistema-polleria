<div class="container-fluid">
    <!-- Alertas -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>⚠️ Errores:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Recepción de Mercadería</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nueva Recepción
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-primary mb-3">
                    <div class="card-header">
                        <h6>Registrar Recepción de Mercadería</h6>
                    </div>
                    <div class="card-body">
                        <!-- Seleccionar Orden -->
                        <div class="form-group mb-3">
                            <label>Orden de Abastecimiento *</label>
                            <select class="form-control" wire:model="idOrden">
                                <option value="">Seleccionar orden...</option>
                                @foreach($ordenes as $orden)
                                    <option value="{{ $orden['idOrden'] }}">
                                        Orden #{{ $orden['idOrden'] }} - {{ $orden['proveedor']['razonSocial'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- TABS -->
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $tabActiva === 'productos' ? 'active' : '' }}" 
                                   wire:click="$set('tabActiva', 'productos')" href="#">
                                    📦 Productos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tabActiva === 'insumos' ? 'active' : '' }}" 
                                   wire:click="$set('tabActiva', 'insumos')" href="#">
                                    🧂 Insumos
                                </a>
                            </li>
                        </ul>

                        <!-- TAB PRODUCTOS -->
                        @if($tabActiva === 'productos')
                            <div class="tab-pane active">
                                <h6 class="mb-3">Productos Recibidos</h6>
                                <button type="button" class="btn btn-success btn-sm mb-3" wire:click="agregarProducto">
                                    ➕ Agregar Producto
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unit.</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($detallesProductos as $index => $detalle)
                                                <tr>
                                                    <td>
                                                        <select class="form-control form-control-sm" 
                                                                wire:model="detallesProductos.{{ $index }}.idProducto"
                                                                wire:change="cargarPrecioProducto({{ $index }})">
                                                            <option value="">Seleccionar...</option>
                                                            @foreach($productos as $prod)
                                                                <option value="{{ $prod['idProducto'] }}">
                                                                    {{ $prod['nombre'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm" 
                                                               wire:model.live="detallesProductos.{{ $index }}.cantidad" 
                                                               min="1">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                                               wire:model.live="detallesProductos.{{ $index }}.precioUnitario" 
                                                               readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                wire:click="eliminarProducto({{ $index }})">
                                                            🗑️
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No hay productos agregados</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- TAB INSUMOS -->
                        @if($tabActiva === 'insumos')
                            <div class="tab-pane active">
                                <h6 class="mb-3">Insumos Recibidos</h6>
                                <button type="button" class="btn btn-success btn-sm mb-3" wire:click="agregarInsumo">
                                    ➕ Agregar Insumo
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Insumo</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unit.</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($detallesInsumos as $index => $detalle)
                                                <tr>
                                                    <td>
                                                        <select class="form-control form-control-sm" 
                                                                wire:model="detallesInsumos.{{ $index }}.idInsumo"
                                                                wire:change="cargarPrecioInsumo({{ $index }})">
                                                            <option value="">Seleccionar...</option>
                                                            @foreach($insumos as $insumo)
                                                                <option value="{{ $insumo['idInsumo'] }}">
                                                                    {{ $insumo['nombreInsumo'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm" 
                                                               wire:model.live="detallesInsumos.{{ $index }}.cantidad"
                                                               min="1">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control form-control-sm" 
                                                               wire:model.live="detallesInsumos.{{ $index }}.precioUnitario" 
                                                               readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                wire:click="eliminarInsumo({{ $index }})">
                                                            🗑️
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No hay insumos agregados</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- RESUMEN -->
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><strong>Subtotal:</strong> S/. {{ number_format($subtotal, 2) }}</p>
                                        <p><strong>IGV (18%):</strong> S/. {{ number_format($igv, 2) }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="h5"><strong>TOTAL:</strong> S/. {{ number_format($totalPagar, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="d-grid gap-2 mt-3">
                            <button type="button" class="btn btn-success btn-lg" wire:click="guardarRecepcion">
                                💾 Guardar Recepción
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="$set('showForm', false)">
                                ❌ Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    El jefe de almacén registra aquí la recepción de mercadería que ha llegado de los proveedores.
                </div>
            @endif
        </div>
    </div>
</div>
