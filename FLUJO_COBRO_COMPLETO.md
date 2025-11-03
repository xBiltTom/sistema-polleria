# Sistema de Pedidos de Salón - Flujo Completo

## Flujo General del Sistema

```
┌─────────────────────────────────────────────────────────────────┐
│                    SISTEMA DE PEDIDOS DE SALÓN                  │
└─────────────────────────────────────────────────────────────────┘

MOZO (Tipo 2):
1. Selecciona Mesa
   ↓
2. Registra Cliente
   ↓
3. Toma Pedido (Platos + Productos)
   ↓
4. Envía a Cocina
   ↓
5. Cocina prepara...
   ↓
6. Mozo entrega a cliente
   ↓
7. Cliente come...
   ↓
8. Mozo COBRA pedido
   ↓
9. Mesa se libera ✓


COCINA (Tipo 3):
- Ve pedidos pendientes
- Inicia preparación de platos
- Marca como completado
- Mozo recibe y entrega
```

## Flujo Detallado: COBRO Y LIBERACIÓN DE MESA

### Fase 1: Mozo Toma el Pedido
**Ruta**: `/mesas` → `/pedido-salon`

1. Selecciona mesa disponible
2. Registra cliente (nombre obligatorio)
3. Busca y agrega platos + productos
4. Registra pedido

**Datos guardados**:
- `estadoPedido` = "pendiente"
- `estadoPago` = "pendiente"
- `totalPedido` = suma de todos los items
- `idMesa` = mesa seleccionada
- Mesa marcada como "ocupada"

---

### Fase 2: Cocina Prepara
**Ruta**: `/pedidos-cocina`

1. Ve pedidos con `estadoPedido` = "pendiente"
2. Para cada PLATO:
   - Click "Iniciar Preparación" → estado = "preparando"
   - Click "Marcar Completado" → estado = "completado"
3. Para cada PRODUCTO:
   - Click "Completado" (sin preparación)
4. Cuando todo está listo:
   - Click "Completar Pedido" → `estadoPedido` = "completado"
5. Click "Marcar Entregado" → `estadoPedido` = "entregado"

---

### Fase 3: Mozo COBRA y Libera Mesa ⭐ **NUEVO**
**Ruta**: `/cobrar-pedido`

#### Paso 1: Seleccionar Pedido a Cobrar
- Sistema muestra lista de pedidos con:
  - `estadoPedido` = "entregado"
  - `estadoPago` = "pendiente"
- Mozo selecciona un pedido
- Se expande mostrando detalles

#### Paso 2: Seleccionar Método de Pago
Opciones:
- **💵 Efectivo**: Requiere ingreso de monto recibido
- **💳 Tarjeta**: Solo confirma (sin cálculo de cambio)
- **🏦 Transferencia**: Solo confirma

#### Paso 3: Registrar Monto (si es efectivo)
- Ingresa monto recibido
- Sistema calcula automáticamente cambio
- Valida que monto sea suficiente

#### Paso 4: Realizar Cobro
Click "💰 Realizar Cobro y Liberar Mesa"

**Sistema ejecuta**:
```php
1. Actualiza pedido:
   - estadoPago = "pagado"
   - totalPedido = cantidad cobrada
   - fechaPago = now()

2. Libera mesa:
   - Mesa estado = "disponible"

3. Muestra resumen:
   - Total cobrado
   - Cambio (si aplica)
   - Mesa liberada
   - Pedido desaparece de la lista
```

---

## Cambios en Base de Datos

### Tabla `pedido` - Nuevas Columnas
```sql
- estadoPago VARCHAR(20) DEFAULT 'pendiente'
  Valores: 'pendiente', 'pagado'
  
- totalPedido DECIMAL(10,2) DEFAULT 0
  Almacena total del pedido
  
- fechaPago DATETIME NULL
  Cuando se realizó el cobro
```

### Estados del Pedido
```
PREPARACIÓN:
pedido.estadoPedido = 'pendiente'  → cocina prepara

ENTREGA:
pedido.estadoPedido = 'entregado'  → cliente come

COBRO:
pedido.estadoPago = 'pagado'       → mesa libre
```

---

## Interfaces

### 1️⃣ Mozo - Toma de Pedidos
**URL**: `/mesas`
- Grid de mesas (disponibles/ocupadas)
- Click para tomar pedido

**URL**: `/pedido-salon`
- Paso 1: Selecciona mesa
- Paso 2: Registra cliente
- Paso 3: Agrega platos y productos
- Botón: "Registrar Pedido"

---

### 2️⃣ Cocina - Preparación
**URL**: `/pedidos-cocina`
- Filtro por estado (Pendientes, Preparando, Completados, Entregados)
- Expandir pedido
- Botones por item:
  - Platos: "Iniciar Preparación" → "Marcar Completado"
  - Productos: "Completado"
- Botones del pedido:
  - "Completar Pedido"
  - "Marcar Entregado"

---

### 3️⃣ Mozo - Cobro y Liberación ⭐ **NUEVO**
**URL**: `/cobrar-pedido`

**Columna Izquierda**:
- Lista de pedidos pendientes de cobro
- Mostra: Mesa, Cliente, Total, Hora entrega
- Click para seleccionar

**Columna Derecha** (cuando se selecciona):
- Detalles del pedido (mesa, cliente, items)
- Total a cobrar
- Selector de método de pago
- Campo de monto recibido (si efectivo)
- Cálculo de cambio automático
- Botón: "Realizar Cobro y Liberar Mesa"

---

## Modelo de Datos Completo

### Pedido (tabla pedido)
```
idPedido (PK)
fechaPedido
estadoPedido: "pendiente" | "completado" | "entregado"
estadoPago: "pendiente" | "pagado"          ← NUEVO
totalPedido: decimal(10,2)                  ← NUEVO
fechaPago: datetime nullable                ← NUEVO
idTipoPedido (FK)
idCliente (FK)
idMesa (FK)
idMozo (FK)
```

### DetallePedido (tabla detalle_pedido)
```
idDetalle (PK)
idPedido (FK)
idPlato (FK) nullable
idProducto (FK) nullable
cantidad
precioUnitario
estado: "pendiente" | "preparando" | "completado"
observacion
```

### Mesa (tabla mesa)
```
idMesa (PK)
nroMesa
capacidadMesa
estado: "disponible" | "ocupada" | "reservada"
```

---

## Casos de Uso

### Caso 1: Pedido Completo (Platos + Productos)
```
1. Mozo registra: 
   - 2x Doble pechuga
   - 2x Gaseosa 2L
   
2. Total: S/ 35.00

3. Cocina prepara platos mientras cliente bebe

4. Entrega completada

5. Mozo cobra S/ 40.00 en efectivo
   → Cambio: S/ 5.00
   → Mesa 3 liberada ✓
```

### Caso 2: Solo Productos (Bebidas/Complementos)
```
1. Mozo registra:
   - 2x Cerveza
   - 1x Helado
   
2. Total: S/ 28.00

3. Cocina marca como "Completado" (sin preparar)

4. Entrega completada

5. Mozo cobra con tarjeta
   → Mesa librada ✓
```

### Caso 3: Efectivo Exacto
```
1. Total: S/ 50.00

2. Mozo ingresa: S/ 50.00

3. Cambio: S/ 0.00 automático

4. Cobro realizado

5. Mesa librada ✓
```

---

## Archivos Modificados/Creados

### Nuevos Archivos
1. `app/Livewire/Mozo/CobrarPedido.php` - Componente de cobro
2. `resources/views/livewire/mozo/cobrar-pedido.blade.php` - Vista del componente
3. `resources/views/mozo/cobrar-pedido.blade.php` - Vista principal
4. `database/migrations/2025_11_03_000008_add_timestamps_to_preparacion_plato.php` - Migraciones de timestamps
5. `database/migrations/2025_11_03_000009_add_pago_fields_to_pedido.php` - Migraciones de pago

### Archivos Modificados
1. `app/Models/Pedido.php` - Agregado fields de pago en fillable
2. `routes/web.php` - Agregada ruta `/cobrar-pedido`
3. `resources/views/layouts/sidebar.blade.php` - Agregado link "💰 Cobrar Pedidos"
4. `app/Livewire/Mozo/PedidoSalon.php` - Guardar totalPedido y estadoPago

---

## Testing Manual - Flujo Completo

### 1. Login como Mozo
```
Usuario: mozo@test.com
Contraseña: password
```

### 2. Crear Pedido
```
URL: /mesas
- Seleccionar Mesa 1
- Ingresar nombre: "Juan Pérez"
- Agregar: 1x Doble pechuga (S/ 18.00)
- Agregar: 2x Gaseosa 2L (S/ 5.00 c/u)
- Total: S/ 28.00
- Registrar Pedido
```

### 3. Verificar en Cocina
```
URL: /pedidos-cocina (como cocinero)
- Expandir Pedido #1
- Plato: "Iniciar Preparación" → "Marcar Completado"
- Gaseosas: "Completado"
- "Completar Pedido"
- "Marcar Entregado"
```

### 4. Cobrar Pedido
```
URL: /cobrar-pedido (como mozo)
- Seleccionar "Mesa 1 - Juan Pérez" (S/ 28.00)
- Método: Efectivo
- Monto recibido: S/ 30.00
- Cambio: S/ 2.00 ✓
- "Realizar Cobro y Liberar Mesa"
- Mensaje: "Cobro realizado: S/ 28.00 | Cambio: S/ 2.00 | Mesa 1 liberada"
```

### 5. Verificar Mesa Liberada
```
URL: /mesas
- Mesa 1 debe estar "disponible" nuevamente
- Puede ser seleccionada para nuevo pedido
```

---

## Próximas Mejoras (Opcionales)

- [ ] Impresión de ticket de cobro
- [ ] Historial de cobros del día
- [ ] Descuentos por cliente
- [ ] Propinas
- [ ] Facturación
- [ ] Reporte de ventas por turno
- [ ] Split payment (dividir pago entre clientes)
- [ ] QR para pago digital
- [ ] Integración con caja registradora

---

## Notas Importantes

1. **Liberación automática de mesa**: Solo ocurre cuando se realiza el cobro completo
2. **Editar pedido**: No se puede editar después de registrado (por diseño)
3. **Cambio de método de pago**: Cambia dinámicamente la UI (monto recibido solo para efectivo)
4. **Validación**: No permite cobro si monto recibido < total (en efectivo)
5. **Seguridad**: Registra fecha de cobro automáticamente

