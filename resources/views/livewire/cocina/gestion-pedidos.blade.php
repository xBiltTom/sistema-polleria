<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestión de Pedidos - Cocina</h1>
            <p class="text-gray-600">Monitoreo y preparación de pedidos del salón</p>
        </div>

        <!-- Notificaciones -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <strong>Error:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <script>
            document.addEventListener('livewire:initialized', function() {
                @this.on('notify', ({type, message}) => {
                    const alertDiv = document.createElement('div');
                    const bgColor = type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700';
                    alertDiv.className = `${bgColor} border px-4 py-3 rounded mb-6 fixed top-4 right-4 z-50 max-w-md`;
                    alertDiv.innerHTML = message;
                    document.body.appendChild(alertDiv);
                    setTimeout(() => alertDiv.remove(), 4000);
                });
            });
        </script>

        <!-- Filtros de Estado -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-2 flex-wrap">
                @foreach (['pendiente' => 'Pendientes', 'preparando' => 'Preparando', 'completado' => 'Completados', 'entregado' => 'Entregados'] as $estado => $label)
                    <button
                        wire:click="$set('filtroEstado', '{{ $estado }}')"
                        class="px-4 py-2 rounded-lg font-semibold transition {{ $filtroEstado === $estado ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Lista de Pedidos -->
        <div class="space-y-4">
            @forelse ($pedidos as $pedido)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <!-- Header del Pedido -->
                    <button
                        wire:click="expandirPedido({{ $pedido->idPedido }})"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition"
                    >
                        <div class="flex-1 text-left">
                            <div class="flex items-center gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Pedido #{{ $pedido->idPedido }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Mesa {{ $pedido->mesa->nroMesa ?? 'N/A' }} • Cliente: {{ $pedido->cliente->nombreCliente ?? 'Sin nombre' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Mozo: {{ $pedido->mozo->nombreEmpleado ?? 'N/A' }} • {{ $pedido->fechaPedido->format('H:i') }}</p>
                                </div>

                                <div>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold border {{ $this->getEstadoClass($pedido->estadoPedido) }}">
                                        {{ ucfirst($pedido->estadoPedido) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-gray-400 transition {{ $pedidoExpandido === $pedido->idPedido ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                    </button>

                    <!-- Detalles del Pedido (Expandible) -->
                    @if ($pedidoExpandido === $pedido->idPedido)
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="space-y-4">
                                @forelse ($pedido->detalles as $detalle)
                                    <div class="bg-white rounded-lg p-4 border-l-4 {{ $this->getEstadoClass($detalle->estado) }}" wire:key="detalle-{{ $detalle->idDetalle }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">
                                                    @if ($detalle->idPlato)
                                                        {{ $detalle->plato->nombrePlato ?? 'Plato desconocido' }}
                                                    @else
                                                        {{ $detalle->producto->nombre ?? 'Producto desconocido' }}
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1">Cantidad: {{ $detalle->cantidad }}</p>
                                                
                                                @if ($detalle->observacion)
                                                    <p class="text-sm text-orange-600 mt-2 italic">
                                                        📝 {{ $detalle->observacion }}
                                                    </p>
                                                @endif
                                            </div>

                                            <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $this->getEstadoClass($detalle->estado) }}">
                                                {{ ucfirst($detalle->estado) }}
                                            </span>
                                        </div>

                                        @if ($detalle->idPlato)
                                            <!-- Botones para platos (preparación) -->
                                            <div class="flex gap-2 mt-4">
                                                @if ($detalle->estado === 'pendiente')
                                                    <button
                                                        wire:click="cambiarEstadoDetalle({{ $detalle->idDetalle }}, 'preparando')"
                                                        class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition"
                                                    >
                                                        Iniciar Preparación
                                                    </button>
                                                @elseif ($detalle->estado === 'preparando')
                                                    <button
                                                        wire:click="cambiarEstadoDetalle({{ $detalle->idDetalle }}, 'completado')"
                                                        class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition"
                                                    >
                                                        Marcar Completado
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Para productos, simplemente marcar como completado si es necesario -->
                                            @if ($detalle->estado === 'pendiente')
                                                <div class="flex gap-2 mt-4">
                                                    <button
                                                        wire:click="cambiarEstadoDetalle({{ $detalle->idDetalle }}, 'completado')"
                                                        class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition"
                                                    >
                                                        Completado
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Sin detalles</p>
                                @endforelse
                            </div>

                            <!-- Botones del Pedido -->
                            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                                @if ($pedido->estadoPedido !== 'completado' && $pedido->estadoPedido !== 'entregado')
                                    <button
                                        wire:click="cambiarEstadoPedido({{ $pedido->idPedido }}, 'completado')"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                    >
                                        Completar Pedido
                                    </button>
                                @endif

                                @if ($pedido->estadoPedido === 'completado')
                                    <button
                                        wire:click="cambiarEstadoPedido({{ $pedido->idPedido }}, 'entregado')"
                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition"
                                    >
                                        Marcar Entregado
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No hay pedidos {{ $filtroEstado }}</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>
