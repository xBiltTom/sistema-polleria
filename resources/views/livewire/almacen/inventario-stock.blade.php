<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>📊 Inventario de Stock</h5>
        </div>
        <div class="card-body">
            <!-- Tabs para Productos/Insumos -->
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

            <!-- Búsqueda -->
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Buscar..." wire:model.live="buscar">
            </div>

            <!-- Tabla de Productos -->
            @if($tipo === 'productos')
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end">Precio Venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $prod)
                                <tr class="{{ $prod->stock <= 5 ? 'table-warning' : '' }}">
                                    <td><strong>#{{ $prod->idProducto }}</strong></td>
                                    <td>{{ $prod->nombre }}</td>
                                    <td>{{ Str::limit($prod->descripcion, 50) }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $prod->stock <= 5 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $prod->stock ?? 0 }} unid.
                                        </span>
                                    </td>
                                    <td class="text-end">S/. {{ number_format($prod->precioVenta, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay productos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $productos->links() }}
            @endif

            <!-- Tabla de Insumos -->
            @if($tipo === 'insumos')
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Insumo</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($insumos as $insumo)
                                <tr class="{{ $insumo->stock <= 5 ? 'table-warning' : '' }}">
                                    <td><strong>#{{ $insumo->idInsumo }}</strong></td>
                                    <td>{{ $insumo->nombreInsumo }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $insumo->stock <= 5 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $insumo->stock ?? 0 }} {{ $insumo->unidadInsumo ?? 'unid.' }}
                                        </span>
                                    </td>
                                    <td class="text-end">S/. {{ number_format($insumo->precioInsumo ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay insumos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $insumos->links() }}
            @endif

            <!-- Resumen -->
            <div class="card bg-light mt-3">
                <div class="card-body">
                    @if($tipo === 'productos')
                        <p class="mb-0">
                            <strong>Total de productos:</strong> {{ $productos->total() }} 
                            | <strong>En bajo stock:</strong> 
                            <span class="badge bg-warning">{{ $productos->count() }}</span>
                        </p>
                    @else
                        <p class="mb-0">
                            <strong>Total de insumos:</strong> {{ $insumos->total() }} 
                            | <strong>En bajo stock:</strong> 
                            <span class="badge bg-warning">{{ $insumos->count() }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
