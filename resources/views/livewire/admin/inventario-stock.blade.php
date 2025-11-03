<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>📊 Inventario Actual de Stock</h5>
        </div>
        <div class="card-body">
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $tipo === 'productos' ? 'active' : '' }}" 
                       wire:click="$set('tipo', 'productos')" href="#">
                        📦 Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tipo === 'insumos' ? 'active' : '' }}" 
                       wire:click="$set('tipo', 'insumos')" href="#">
                        🧂 Insumos
                    </a>
                </li>
            </ul>

            <!-- Controles -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="🔍 Buscar..." wire:model.live="buscar">
                </div>
                <div class="col-md-6">
                    <select class="form-select" wire:model.live="ordenarPor">
                        <option value="stock">Ordenar por: Stock (menor primero)</option>
                        <option value="nombre">Ordenar por: Nombre</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de Productos -->
            @if($tipo === 'productos')
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th style="width: 100px;" class="text-center">Stock</th>
                                <th style="width: 120px;" class="text-end">Precio Venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $prod)
                                <tr class="{{ $prod->stock <= 5 ? 'table-warning' : ($prod->stock == 0 ? 'table-danger' : '') }}">
                                    <td><strong>#{{ $prod->idProducto }}</strong></td>
                                    <td>
                                        <strong>{{ $prod->nombre }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($prod->descripcion ?? 'Sin descripción', 40) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($prod->stock == 0)
                                            <span class="badge bg-danger">AGOTADO</span>
                                        @elseif($prod->stock <= 5)
                                            <span class="badge bg-warning">{{ $prod->stock }} unid.</span>
                                        @else
                                            <span class="badge bg-success">{{ $prod->stock }} unid.</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>S/. {{ number_format($prod->precioVenta ?? 0, 2) }}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No hay productos
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <nav>
                    {{ $productos->links() }}
                </nav>
            @endif

            <!-- Tabla de Insumos -->
            @if($tipo === 'insumos')
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Insumo</th>
                                <th style="width: 100px;" class="text-center">Stock</th>
                                <th style="width: 100px;">Unidad</th>
                                <th style="width: 120px;" class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($insumos as $insumo)
                                <tr class="{{ $insumo->stock <= 5 ? 'table-warning' : ($insumo->stock == 0 ? 'table-danger' : '') }}">
                                    <td><strong>#{{ $insumo->idInsumo }}</strong></td>
                                    <td>
                                        <strong>{{ $insumo->nombreInsumo }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($insumo->stock == 0)
                                            <span class="badge bg-danger">AGOTADO</span>
                                        @elseif($insumo->stock <= 5)
                                            <span class="badge bg-warning">{{ $insumo->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $insumo->stock }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $insumo->unidadInsumo ?? 'unid.' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <strong>S/. {{ number_format($insumo->precioInsumo ?? 0, 2) }}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No hay insumos
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <nav>
                    {{ $insumos->links() }}
                </nav>
            @endif

            <!-- Estadísticas -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-0"><strong>📦 Total items:</strong></p>
                            <h4>{{ $tipo === 'productos' ? $productos->total() : $insumos->total() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning bg-opacity-25">
                        <div class="card-body">
                            <p class="mb-0"><strong>⚠️ Bajo stock:</strong></p>
                            <h4>{{ $tipo === 'productos' ? $productos->where('stock', '<=', 5)->count() : $insumos->where('stock', '<=', 5)->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger bg-opacity-25">
                        <div class="card-body">
                            <p class="mb-0"><strong>🚨 Agotados:</strong></p>
                            <h4>{{ $tipo === 'productos' ? $productos->where('stock', 0)->count() : $insumos->where('stock', 0)->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success bg-opacity-25">
                        <div class="card-body">
                            <p class="mb-0"><strong>✅ Disponibles:</strong></p>
                            <h4>{{ $tipo === 'productos' ? $productos->where('stock', '>', 5)->count() : $insumos->where('stock', '>', 5)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
