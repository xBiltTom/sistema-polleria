<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">💰 Cobrar Pedidos</h1>
            <p class="text-gray-600 text-sm md:text-base">Realiza el cobro y libera las mesas</p>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Columna Izquierda: Lista de Pedidos Entregados -->
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-4">📋 Pendientes de Cobro</h2>

                @if (empty($pedidosEntregados))
                    <div class="text-center text-gray-500 py-8">
                        <p class="text-sm md:text-base">No hay pedidos pendientes de cobro</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach ($pedidosEntregados as $pedido)
                            <button
                                wire:click="seleccionarPedido({{ $pedido['idPedido'] }})"
                                class="w-full text-left p-3 md:p-4 rounded-lg border-2 transition {{ $pedidoSeleccionado === $pedido['idPedido'] ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-gray-50 hover:border-gray-300' }}"
                            >
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-semibold text-gray-900 text-sm md:text-base">
                                            Mesa {{ $pedido['nroMesa'] }} - {{ $pedido['cliente'] }}
                                        </div>
                                        <div class="text-xs md:text-sm text-gray-600 mt-1">
                                            Entregado: {{ $pedido['horaEntrega'] }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-gray-900 text-sm md:text-base">
                                            S/ {{ number_format($pedido['total'], 2) }}
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Columna Derecha: Detalles y Cobro -->
            @if ($pedidoActual)
                <div class="bg-white rounded-lg shadow p-4 md:p-6 lg:sticky lg:top-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg md:text-xl font-bold text-gray-900">Detalles del Pedido</h2>
                        <button
                            wire:click="cerrarPedido"
                            class="text-gray-500 hover:text-gray-700 text-xl"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Info del Pedido -->
                    <div class="bg-gray-50 p-3 md:p-4 rounded-lg mb-4">
                        <div class="grid grid-cols-2 gap-3 md:gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 text-xs">Mesa</p>
                                <p class="font-bold text-gray-900">{{ $pedidoActual->mesa->nroMesa ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs">Cliente</p>
                                <p class="font-bold text-gray-900">{{ $pedidoActual->cliente->nombreCliente ?? 'Sin nombre' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs">Pedido #</p>
                                <p class="font-bold text-gray-900">#{{ $pedidoActual->idPedido }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs">Hora</p>
                                <p class="font-bold text-gray-900">{{ $pedidoActual->fechaPedido->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de Items -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-gray-900 mb-2 text-sm">Items del Pedido</h3>
                        <div class="bg-gray-50 rounded-lg p-3 md:p-4 max-h-40 overflow-y-auto">
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($pedidoActual->detalles as $detalle)
                                @php
                                    $subtotal = $detalle->cantidad * $detalle->precioUnitario;
                                    $total += $subtotal;
                                @endphp
                                <div class="flex justify-between text-xs md:text-sm mb-2">
                                    <span>
                                        {{ $detalle->idPlato ? $detalle->plato->nombrePlato : $detalle->producto->nombre }}
                                        <span class="text-gray-600">(x{{ $detalle->cantidad }})</span>
                                    </span>
                                    <span class="font-semibold">S/ {{ number_format($subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Resumen de Cobro -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-3 md:p-4 mb-4">
                        <div class="flex justify-between items-center mb-3 pb-3 border-b-2 border-blue-200">
                            <span class="font-semibold text-gray-900">Total a Cobrar:</span>
                            <span class="text-xl md:text-2xl font-bold text-blue-600">S/ {{ number_format($total, 2) }}</span>
                        </div>

                        <!-- Método de Pago -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                            <select
                                wire:model="metodoPago"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            >
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="tarjeta">💳 Tarjeta</option>
                                <option value="transferencia">🏦 Transferencia</option>
                            </select>
                        </div>

                        <!-- Monto Recibido (Solo si es efectivo) -->
                        @if ($metodoPago === 'efectivo')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Monto Recibido</label>
                                <input
                                    type="number"
                                    wire:model.live="montoRecibido"
                                    placeholder="0.00"
                                    step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                />
                            </div>

                            <!-- Cambio -->
                            <div class="bg-white border-2 border-green-300 rounded-lg p-3 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Cambio:</span>
                                    <span class="text-lg md:text-xl font-bold text-green-600">
                                        S/ {{ number_format($this->calcularCambio(), 2) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Botón Cobrar -->
                    <button
                        wire:click="realizarCobro"
                        class="w-full px-4 py-3 md:py-4 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition text-sm md:text-base"
                    >
                        💰 Realizar Cobro y Liberar Mesa
                    </button>
                </div>
            @else
                <!-- Sin pedido seleccionado -->
                <div class="bg-white rounded-lg shadow p-4 md:p-6 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <p class="text-base md:text-lg">Selecciona un pedido para cobrar</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
