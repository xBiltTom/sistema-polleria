# Actualizaciones del Sistema de Pedidos de Salón

## Cambios Realizados - Sesión Actual

### 1. **Separación clara entre PLATOS y PRODUCTOS (AÑADIDOS)**

#### Conceptos:
- **PLATOS**: Comidas principales (Pollería, porciones, etc.)
- **PRODUCTOS**: Añadidos complementarios (Gaseosas, helados, juegos, etc.)

#### Cambios en Vista:
- **resources/views/livewire/mozo/pedido-salon.blade.php**
  - Sección "🍗 Platos Principales" con descripción "Comidas completas para el cliente"
  - Sección "🥤 Añadidos (Productos)" con descripción "Gaseosas, helados, juegos y más"
  - Resumen separado por tipo:
    - Platos en azul con bordes `border-l-4 border-blue-500`
    - Productos en verde con bordes `border-l-4 border-green-500`

#### Cambios en Componente:
- **app/Livewire/Mozo/PedidoSalon.php**
  - Eliminados métodos helpers duplicados `buscarPlatosHelper()` y `buscarProductosHelper()`
  - Se mantienen @Computed properties para búsqueda reactiva
  - Métodos `agregarPlato()` y `agregarProducto()` manejan cada tipo independientemente

### 2. **Corrección de Rutas**

**Problema**: Ruta `pedidos.cocina` no definida en sidebar
**Archivo**: `resources/views/layouts/sidebar.blade.php` línea 67
**Cambio**: `route('pedidos.cocina')` → `route('cocina.pedidos')`
**Razón**: Las rutas en `routes/web.php` definen como `cocina.pedidos`

### 3. **Mejoras en GestionPedidos (Cocina)**

**Archivo**: `app/Livewire/Cocina/GestionPedidos.php`

#### Cambio 1 - Carga de relaciones:
```php
// ANTES
$pedidos = Pedido::where('estadoPedido', $this->filtroEstado)
    ->with(['cliente', 'mesa', 'mozo'])
    ->paginate(10);

// DESPUÉS
$pedidos = Pedido::where('estadoPedido', $this->filtroEstado)
    ->with(['cliente', 'mesa', 'mozo', 'detalles.plato', 'detalles.producto'])
    ->paginate(10);
```

**Razón**: Necesita cargar `detalles` con sus relaciones `plato` y `producto` para mostrar en la vista

#### Cambio 2 - Refresco de datos:
```php
public function cambiarEstadoDetalle($idDetalle, $nuevoEstado)
{
    // ... código de actualización ...
    $this->dispatch('refresh');  // ← NUEVO
}

public function cambiarEstadoPedido($idPedido, $nuevoEstado)
{
    // ... código de actualización ...
    $this->dispatch('refresh');  // ← NUEVO
}
```

**Razón**: Livewire necesita que el componente se reutilice después de cambios para mostrar el nuevo estado

### 4. **Cambios en Vista de Cocina**

**Archivo**: `resources/views/livewire/cocina/gestion-pedidos.blade.php`

#### Cambio: Wire key para renderizado correcto
```blade
@forelse ($pedido->detalles as $detalle)
    <div class="bg-white rounded-lg p-4 border-l-4 {{ $this->getEstadoClass($detalle->estado) }}" wire:key="detalle-{{ $detalle->idDetalle }}">
```

**Razón**: Permite a Livewire identificar correctamente cada item para actualizaciones

## Estado de la Migración

✅ **Migración 2025_11_03_000007 ejecutada**: `idPlato` ahora es nullable
- Permite insertar `detalle_pedido` con solo `idProducto` (sin plato)
- Ejemplo: Añadir una gaseosa sin plato principal

## Flujo Completo Actualizado

### Para el Mozo (Toma de Pedidos):
1. Selecciona mesa disponible
2. Registra cliente (nombre obligatorio, DNI/celular opcionales)
3. Busca y agrega PLATOS (cualquier cantidad)
4. Busca y agrega PRODUCTOS/AÑADIDOS (gaseosas, helados, etc.)
5. Modifica observaciones por item
6. Registra pedido → Se marca mesa como OCUPADA

**Ejemplo de Pedido Válido**:
- 1x Doble pechuga con papas (PLATO)
- 1x Cuarto de pollo (PLATO)
- 2x Gaseosa 2L (PRODUCTO)
- 1x Helado (PRODUCTO)

**Ejemplo Alternativo** (solo productos):
- 2x Gaseosa 2L (PRODUCTO)
- 1x Cerveza (PRODUCTO)
- (Sin platos - válido para bebidas/complementos)

### Para la Cocina (Gestión de Preparación):
1. Ve pedidos filtrados por estado (Pendientes, Preparando, Completados, Entregados)
2. Expande pedido para ver detalles
3. Para cada PLATO:
   - Click "Iniciar Preparación" (estado → preparando)
   - Click "Marcar Completado" (estado → completado)
4. Para cada PRODUCTO:
   - Solo "Completado" (no requiere preparación)
5. Botones de pedido:
   - "Completar Pedido" (cuando todos los items están listos)
   - "Marcar Entregado" (cuando se lleva a la mesa)

## Notas Técnicas

### Tabla detalle_pedido estructura:
```
- idDetalle (PK)
- idPedido (FK → pedido)
- idPlato (FK → plato) ← AHORA NULLABLE
- idProducto (FK → producto) ← NULLABLE desde creación
- cantidad
- precioUnitario
- estado (pendiente, preparando, completado)
- observacion
```

### Estados posibles por tipo:
**PLATOS**: pendiente → preparando → completado
**PRODUCTOS**: pendiente → completado (sin preparación)

### Validación:
- Búsqueda requiere mínimo 2 caracteres
- Solo muestra 5 resultados máximo
- Solo muestra items con stock > 0
- No permite agregar 0 cantidad

## Testing Manual

### Paso 1: Crear Pedido de Prueba
1. Login como Mozo (tipo 2)
2. Ver mesas disponibles
3. Seleccionar una mesa
4. Registrar cliente: "Juan Pérez"
5. Buscar y agregar: "Doble pechuga" (PLATO)
6. Buscar y agregar: "Gaseosa 2L" (PRODUCTO)
7. Registrar pedido

### Paso 2: Verificar en Cocina
1. Login como Cocinero (tipo 3)
2. Ver pedidos en estado "Pendiente"
3. Expandir el pedido creado
4. Click en "Iniciar Preparación" para el plato
5. Click en "Completado" para la gaseosa
6. Click en "Marcar Completado" para el plato
7. Click en "Completar Pedido" (bottom)
8. Click en "Marcar Entregado"

### Paso 3: Verificar filtros
- Ver en "Completados" el pedido recién entregado
- Ver en "Entregados" el pedido

## Posibles Problemas y Soluciones

### Problema: Botón no responde
- ✅ Verificar que los detalles cargan con `with(['detalles.plato', 'detalles.producto'])`
- ✅ Agregar `wire:key="detalle-{{ $detalle->idDetalle }}"` en items

### Problema: Estados no actualizan
- ✅ Usar `$this->dispatch('refresh')` después de `update()`
- ✅ Verificar que `render()` se ejecuta nuevamente

### Problema: Hijos no aparecen
- ✅ Cargar relaciones en el query builder con `with()`
- ✅ Verificar que los modelos tienen las relaciones definidas

## Archivos Modificados

1. `app/Livewire/Mozo/PedidoSalon.php` - Eliminar helpers, mantener @Computed
2. `resources/views/livewire/mozo/pedido-salon.blade.php` - Separar UI, colores por tipo
3. `resources/views/layouts/sidebar.blade.php` - Corregir nombre de ruta
4. `app/Livewire/Cocina/GestionPedidos.php` - Cargar detalles, agregar refresh
5. `resources/views/livewire/cocina/gestion-pedidos.blade.php` - Agregar wire:key

## Próximos Pasos (Opcionales)

- [ ] Agregar impresión de ticket
- [ ] Agregar notificaciones en tiempo real (Pusher/Broadcasting)
- [ ] Agregar historial de cambios de estado
- [ ] Agregar fotos de platos
- [ ] Agregar confirmación antes de marcar entregado
- [ ] Agregar tiempo de preparación estimado por plato
