<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-full">
        <!-- Header -->
        <div class="mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">🍽️ Venta en Sala</h1>
            <p class="text-gray-600 text-sm md:text-base">{{ session('pedido_cliente_nombre') ?? 'Sistema de Toma de Pedidos' }}</p>
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

        <!-- PASO 1: Seleccionar Mesa -->
        @if ($step === 'seleccionar-mesa')
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Selecciona una Mesa</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($mesas as $mesa)
                        <button
                            wire:click="seleccionarMesa({{ $mesa['idMesa'] }})"
                            @disabled($mesa['estado'] !== 'disponible')
                            class="group relative rounded-lg border-2 {{ $mesa['estado'] !== 'disponible' ? 'cursor-not-allowed opacity-50' : 'hover:shadow-xl cursor-pointer transition-all' }}"
                        >
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 md:px-6 py-3 md:py-4 rounded-t-md">
                                <h3 class="text-lg md:text-2xl font-bold">Mesa {{ $mesa['nroMesa'] }}</h3>
                            </div>

                            <!-- Card Body -->
                            <div class="bg-white px-4 md:px-6 py-4 md:py-6 rounded-b-md space-y-2 md:space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700">Capacidad:</span>
                                    <span class="text-sm md:text-base font-semibold text-gray-900">{{ $mesa['capacidadMesa'] }} personas</span>
                                </div>

                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-700">Estado:</span>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs md:text-sm font-semibold {{ $mesa['estado'] === 'disponible' ? 'bg-green-100 text-green-800' : ($mesa['estado'] === 'ocupada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($mesa['estado']) }}
                                    </span>
                                </div>

                                @if ($mesa['estado'] === 'disponible')
                                    <div class="pt-2 md:pt-3">
                                        <div class="w-full px-4 py-2 md:py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition text-sm md:text-base">
                                            ➕ Nuevo Pedido
                                        </div>
                                    </div>
                                @else
                                    <div class="pt-2 md:pt-3">
                                        <div class="w-full px-4 py-2 md:py-3 bg-gray-300 text-gray-600 font-bold rounded-lg text-sm md:text-base text-center">
                                            Mesa No Disponible
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- PASO 2: Registrar Cliente -->
        @if ($step === 'registrar-cliente')
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 md:mb-6">Registrar Cliente - Mesa {{ $mesas[array_search($mesaSeleccionada, array_column($mesas, 'idMesa'))]['nroMesa'] ?? '' }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4 mb-4 md:mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input
                            type="text"
                            wire:model="nombreCliente"
                            placeholder="Nombre del cliente"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        />
                        @error('nombreCliente') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                        <input
                            type="text"
                            wire:model="dniCliente"
                            placeholder="DNI del cliente"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                        <input
                            type="text"
                            wire:model="celularCliente"
                            placeholder="Celular del cliente"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        />
                    </div>
                </div>

                <div class="flex gap-3 md:gap-4">
                    <button
                        wire:click="volverAMesas"
                        class="px-4 md:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm md:text-base"
                    >
                        Volver
                    </button>
                    <button
                        wire:click="registrarClienteYProceder"
                        class="px-4 md:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm md:text-base"
                    >
                        Continuar
                    </button>
                </div>
            </div>
        @endif

        <!-- PASO 3: Tomar Pedido -->
        @if ($step === 'tomar-pedido')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Columna Izquierda: Búsqueda y Selección -->
                <div class="lg:col-span-2">
                    <!-- Búsqueda de Platos -->
                    <div class="bg-white rounded-lg shadow p-4 md:p-6 mb-4 md:mb-6 border-l-4 border-blue-500">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-1 md:mb-2">🍗 Platos Principales</h3>
                        <p class="text-xs md:text-sm text-gray-600 mb-3 md:mb-4">Comidas completas para el cliente</p>
                        <div class="mb-4">
                            <input
                                type="text"
                                wire:model.live="busquedaPlatos"
                                placeholder="Buscar platos..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            />
                        </div>

                        @if (!empty($this->platosBuscados))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                                @foreach ($this->platosBuscados as $plato)
                                    <button
                                        wire:click="agregarPlato({{ $plato['idPlato'] }})"
                                        type="button"
                                        class="p-3 md:p-4 border-2 border-blue-300 bg-blue-50 rounded-lg hover:bg-blue-100 transition text-left text-sm md:text-base"
                                    >
                                        <div class="font-semibold text-gray-900">{{ $plato['nombrePlato'] }}</div>
                                        <div class="text-xs md:text-sm text-gray-600 mt-1">S/ {{ number_format($plato['precioVenta'], 2) }}</div>
                                        <div class="text-xs text-green-600 mt-1">✅ Disponible</div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Búsqueda de Productos -->
                    <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-1 md:mb-2">🥤 Añadidos (Productos)</h3>
                        <p class="text-xs md:text-sm text-gray-600 mb-3 md:mb-4">Gaseosas, helados, juegos y más</p>
                        <div class="mb-4">
                            <input
                                type="text"
                                wire:model.live="busquedaProductos"
                                placeholder="Buscar productos..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                            />
                        </div>

                        @if (!empty($this->productosBuscados))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                                @foreach ($this->productosBuscados as $producto)
                                    <button
                                        wire:click="agregarProducto({{ $producto['idProducto'] }})"
                                        type="button"
                                        class="p-3 md:p-4 border-2 border-green-300 bg-green-50 rounded-lg hover:bg-green-100 transition text-left text-sm md:text-base"
                                    >
                                        <div class="font-semibold text-gray-900">{{ $producto['nombre'] }}</div>
                                        <div class="text-xs md:text-sm text-gray-600 mt-1">S/ {{ number_format($producto['precioVenta'], 2) }}</div>
                                        <div class="text-xs text-green-600 mt-1">Stock: {{ $producto['stock'] }}</div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Columna Derecha: Resumen del Pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-4 md:p-6 lg:sticky lg:top-4">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3 md:mb-4">📋 Resumen del Pedido</h3>

                        @if (empty($pedidoItems))
                            <div class="text-center text-gray-500 py-6 md:py-8 text-sm">
                                No hay items en el pedido
                            </div>
                        @else
                            <div class="space-y-2 md:space-y-4 mb-4 md:mb-6 max-h-64 md:max-h-80 overflow-y-auto">
                                <!-- Platos -->
                                @php
                                    $platos = array_filter($pedidoItems, fn($item) => $item['tipo'] === 'plato');
                                    $productos = array_filter($pedidoItems, fn($item) => $item['tipo'] === 'producto');
                                @endphp

                                @if (!empty($platos))
                                    <div class="pb-2 md:pb-3 mb-2 md:mb-3 border-b-2 border-blue-300">
                                        <div class="text-xs md:text-sm font-bold text-blue-700 mb-2">🍗 PLATOS</div>
                                        @foreach ($platos as $key => $item)
                                            <div class="border border-blue-200 bg-blue-50 rounded-lg p-2 md:p-3 text-sm mb-2 md:mb-3">
                                                <div class="font-semibold text-gray-900 text-xs md:text-sm">{{ $item['nombre'] }}</div>
                                                <div class="text-xs text-gray-600 mt-1">S/ {{ number_format($item['precio'], 2) }} c/u</div>

                                                <div class="flex items-center gap-2 mt-2">
                                                    <button
                                                        wire:click="actualizarCantidad('{{ $key }}', {{ $item['cantidad'] - 1 }})"
                                                        class="px-2 py-1 bg-blue-200 rounded text-xs hover:bg-blue-300"
                                                    >
                                                        -
                                                    </button>
                                                    <span class="flex-1 text-center text-xs font-semibold">{{ $item['cantidad'] }}</span>
                                                    <button
                                                        wire:click="actualizarCantidad('{{ $key }}', {{ $item['cantidad'] + 1 }})"
                                                        class="px-2 py-1 bg-blue-200 rounded text-xs hover:bg-blue-300"
                                                    >
                                                        +
                                                    </button>
                                                </div>

                                                <div class="text-xs font-bold text-blue-900 mt-2">
                                                    S/ {{ number_format($item['subtotal'], 2) }}
                                                </div>

                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        wire:model="pedidoItems.{{ $key }}.observacion"
                                                        wire:change="actualizarObservacion('{{ $key }}', $event.target.value)"
                                                        placeholder="Obs..."
                                                        class="w-full px-2 py-1 text-xs border border-blue-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                    />
                                                </div>

                                                <button
                                                    wire:click="eliminarItem('{{ $key }}')"
                                                    class="mt-2 w-full px-2 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Productos/Añadidos -->
                                @if (!empty($productos))
                                    <div class="pb-2 md:pb-3 mb-2 md:mb-3">
                                        <div class="text-xs md:text-sm font-bold text-green-700 mb-2">🥤 AÑADIDOS</div>
                                        @foreach ($productos as $key => $item)
                                            <div class="border border-green-200 bg-green-50 rounded-lg p-2 md:p-3 text-sm mb-2 md:mb-3">
                                                <div class="font-semibold text-gray-900 text-xs md:text-sm">{{ $item['nombre'] }}</div>
                                                <div class="text-xs text-gray-600 mt-1">S/ {{ number_format($item['precio'], 2) }} c/u</div>

                                                <div class="flex items-center gap-2 mt-2">
                                                    <button
                                                        wire:click="actualizarCantidad('{{ $key }}', {{ $item['cantidad'] - 1 }})"
                                                        class="px-2 py-1 bg-green-200 rounded text-xs hover:bg-green-300"
                                                    >
                                                        -
                                                    </button>
                                                    <span class="flex-1 text-center text-xs font-semibold">{{ $item['cantidad'] }}</span>
                                                    <button
                                                        wire:click="actualizarCantidad('{{ $key }}', {{ $item['cantidad'] + 1 }})"
                                                        class="px-2 py-1 bg-green-200 rounded text-xs hover:bg-green-300"
                                                    >
                                                        +
                                                    </button>
                                                </div>

                                                <div class="text-xs font-bold text-green-900 mt-2">
                                                    S/ {{ number_format($item['subtotal'], 2) }}
                                                </div>

                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        wire:model="pedidoItems.{{ $key }}.observacion"
                                                        wire:change="actualizarObservacion('{{ $key }}', $event.target.value)"
                                                        placeholder="Obs..."
                                                        class="w-full px-2 py-1 text-xs border border-green-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500"
                                                    />
                                                </div>

                                                <button
                                                    wire:click="eliminarItem('{{ $key }}')"
                                                    class="mt-2 w-full px-2 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="border-t-2 border-gray-200 pt-3 md:pt-4">
                                <div class="flex justify-between items-center mb-3 md:mb-4">
                                    <span class="text-base md:text-lg font-bold text-gray-900">Total:</span>
                                    <span class="text-xl md:text-2xl font-bold text-blue-600">S/ {{ number_format($totalPedido, 2) }}</span>
                                </div>

                                <button
                                    wire:click="registrarPedido"
                                    class="w-full px-4 py-2 md:py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition text-sm md:text-base"
                                >
                                    Registrar Pedido
                                </button>
                            </div>
                        @endif

                        <button
                            wire:click="volverACliente"
                            class="w-full mt-3 md:mt-4 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-sm md:text-base"
                        >
                            Volver
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
