<aside class="bg-dark text-white" style="width: 250px; min-height: 100vh; overflow-y: auto;">
    <div class="p-4">
        <h5 class="mb-4">Sistema Pollería</h5>
        
        <nav class="nav flex-column">
            <a class="nav-link text-white mb-2" href="{{ route('dashboard') }}">
                📊 Dashboard
            </a>

            @php
                $tipoEmpleado = auth()->user()->empleado->idTipoEmpleado ?? null;
            @endphp

            @if($tipoEmpleado == 1)
                <hr class="my-2">
                <a class="nav-link text-white mb-2" href="{{ route('insumos.index') }}">
                    🧂 Insumos
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('platos.index') }}">
                    🍗 Platos
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('productos.index') }}">
                    📦 Productos
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('proveedores.index') }}">
                    🏢 Proveedores
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('mesas.index') }}">
                    🪑 Mesas
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('empleados.index') }}">
                    👥 Empleados
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('ventas.index') }}">
                    💰 Ventas
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('comprobantes.index') }}">
                    🧾 Comprobantes
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('almacen.index') }}">
                    📦 Almacén
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('reportes.index') }}">
                    📈 Reportes
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('admin.inventario') }}">
                    📊 Stock General
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('admin.pagos') }}">
                    💳 Pagos a Proveedores
                </a>

            @elseif($tipoEmpleado == 2)
                <hr class="my-2">
                <a class="nav-link text-white mb-2" href="{{ route('mozo.mesas') }}">
                    🪑 Mesas
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('ventas.sala') }}">
                    🍽️ Venta Sala
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('ventas.delivery') }}">
                    🚚 Delivery
                </a>

            @elseif($tipoEmpleado == 3)
                <hr class="my-2">
                <a class="nav-link text-white mb-2" href="{{ route('pedidos.cocina') }}">
                    👨‍🍳 Pedidos Cocina
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('preparacion.index') }}">
                    ⏱️ Preparación
                </a>

            @elseif($tipoEmpleado == 4)
                <hr class="my-2">
                <a class="nav-link text-white mb-2" href="{{ route('almacen.productos') }}">
                    📦 Productos
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('almacen.ordenes') }}">
                    📋 Órdenes de Suministro
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('almacen.recepcion') }}">
                    📥 Recepción de Mercadería
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('almacen.inventario') }}">
                    📊 Inventario
                </a>
                <a class="nav-link text-white mb-2" href="{{ route('almacen.compras') }}">
                    🛒 Compras
                </a>

            @endif

            <hr class="my-3">
            
            <a class="nav-link text-white mb-2" href="{{ route('profile.edit') }}">
                ⚙️ Mi Perfil
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">Cerrar Sesión</button>
            </form>
        </nav>
    </div>
</aside>
