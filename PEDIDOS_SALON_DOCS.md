# Sistema de Pedidos de Salón - Documentación

## ✅ Cambios Implementados (3 de Noviembre 2025)

### 1. Componentes Livewire
- **PedidoSalon** (`app/Livewire/Mozo/PedidoSalon.php`)
  - Paso 1: Seleccionar mesa disponible
  - Paso 2: Registrar datos del cliente
  - Paso 3: Tomar pedido (búsqueda y agregación de platos/productos)
  - Propiedades Computed para búsquedas reactivas
  - Cálculo automático de totales
  
- **GestionPedidos** (`app/Livewire/Cocina/GestionPedidos.php`)
  - Filtrado por estado (Pendiente, Preparando, Completado, Entregado)
  - Expansión de pedidos para ver detalles
  - Cambio de estado de items y pedidos
  - Registro automático de tiempos de preparación

### 2. Vistas Blade Responsivas
- **mozo/mesas.blade.php** - Grid responsive (2 cols mobile, 3 cols tablet, 4 cols desktop)
- **mozo/pedido-salon.blade.php** - 3 pasos con diseño adaptable
- **cocina/pedidos.blade.php** - Listado expandible de pedidos

### 3. Migraciones
- **2025_11_03_000002**: Agregar soporte de productos a `detalle_pedido`
- Corregidas migraciones anteriores para evitar conflictos en rollback

### 4. Seeders
- **MesaSeeder**: 10 mesas (3 pequeñas, 4 medianas, 3 grandes)
- Todos los datos de test generados automáticamente

### 5. Rutas
- `/pedido-salon` → Toma de pedidos (Mozo)
- `/pedidos-cocina` → Gestión de pedidos (Cocinero)
- `/mesas` → Vista de mesas disponibles (Mozo)

El sistema de pedidos de salón permite a los mozos registrar pedidos de clientes sentados en mesas, verificar el stock de productos y platos, y enviar los pedidos a cocina para su preparación.

## Flujo del Sistema

### 1. MOZO - Tomar Pedido (Ruta: `/pedido-salon`)

El mozo sigue estos pasos:

#### Paso 1: Seleccionar Mesa
- Ve todas las mesas disponibles (estado = 'disponible')
- Las mesas ocupadas o reservadas están deshabilitadas
- Cada mesa muestra: número, capacidad y estado actual

#### Paso 2: Registrar Cliente
- Ingresa: Nombre (obligatorio), DNI y Celular (opcionales)
- El sistema busca o crea el cliente automáticamente
- Los datos se guardan en sesión

#### Paso 3: Tomar Pedido
- **Búsqueda de Platos**: Busca por nombre o descripción
  - Solo muestra platos con stock > 0
  - Cada búsqueda debe tener mínimo 2 caracteres
  - Limite: 5 resultados
  
- **Búsqueda de Productos**: Similar a platos
  - Productos con stock > 0
  - Se pueden mezclar productos y platos en un mismo pedido

- **Resumen del Pedido**:
  - Muestra todos los items agregados
  - Permite modificar cantidad (+ -)
  - Permite agregar observaciones (ej: "Sin sal", "Extra picante")
  - Calcula el total automáticamente
  - Botón para eliminar items

#### Paso 4: Registrar Pedido
- Al registrar:
  - Se crea el pedido con estado = 'pendiente'
  - Se crean los detalles del pedido (items)
  - La mesa se marca como 'ocupada'
  - El pedido se envía a la vista de cocina

## 2. COCINA - Gestión de Pedidos (Ruta: `/pedidos-cocina`)

El cocinero verifica y prepara los pedidos.

### Características:

#### Filtros de Estado
- **Pendientes**: Pedidos nuevos sin empezar
- **Preparando**: Pedidos en preparación
- **Completados**: Listos para ser entregados
- **Entregados**: Pedidos entregados al cliente

#### Información del Pedido
Cada pedido muestra:
- Número de pedido
- Número de mesa
- Nombre del cliente
- Mozo que tomó el pedido
- Hora del pedido
- Estado actual

#### Detalles del Pedido (Expandible)
Al expandir un pedido se ven todos los items con:
- Nombre del plato/producto
- Cantidad
- Observaciones especiales (si las hay)
- Estado del item (pendiente, preparando, completado)

#### Acciones en Cocina

**Para Platos:**
1. Clic en "Iniciar Preparación" → cambia estado a 'preparando'
2. Clic en "Marcar Completado" → cambia estado a 'completado'

**Para Productos:**
- Simplemente clic en "Completado" cuando esté listo

**Para Pedidos:**
1. Una vez todos los items estén completados
2. Clic en "Completar Pedido" → el pedido cambia a 'completado'
3. Clic en "Marcar Entregado" → pedido entregado, mesa queda libre

### Estados del Sistema

```
PEDIDO:
pendiente → preparando → completado → entregado

DETALLE (Item):
pendiente → preparando → completado

MESA:
disponible → ocupada → disponible
```

## Base de Datos - Tablas Principales

### `mesa`
```sql
CREATE TABLE mesa (
  idMesa INT PRIMARY KEY AUTO_INCREMENT,
  nroMesa INT,
  capacidadMesa INT,
  descripcionMesa VARCHAR(30),
  estado ENUM('disponible', 'ocupada', 'reservada') DEFAULT 'disponible',
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### `pedido`
```sql
CREATE TABLE pedido (
  idPedido INT PRIMARY KEY AUTO_INCREMENT,
  fechaPedido DATETIME,
  estadoPedido VARCHAR(20),
  direccionEntrega VARCHAR(60) NULL,
  descripcionPedido VARCHAR(60) NULL,
  idTipoPedido INT,
  idCliente INT,
  idMesa INT,
  idMozo INT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (idMesa) REFERENCES mesa(idMesa),
  FOREIGN KEY (idMozo) REFERENCES empleado(idEmpleado),
  FOREIGN KEY (idCliente) REFERENCES cliente(idCliente),
  FOREIGN KEY (idTipoPedido) REFERENCES tipo_pedido(idTipoPedido)
)
```

### `detalle_pedido`
```sql
CREATE TABLE detalle_pedido (
  idDetalle INT PRIMARY KEY AUTO_INCREMENT,
  idPedido INT,
  precioUnitario DECIMAL(10, 2),
  cantidad INT,
  descripcion VARCHAR(45) NULL,
  estado VARCHAR(30) NULL,
  observacion VARCHAR(45) NULL,
  idPlato INT NULL,
  idProducto INT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (idPedido) REFERENCES pedido(idPedido) ON DELETE CASCADE,
  FOREIGN KEY (idPlato) REFERENCES plato(idPlato),
  FOREIGN KEY (idProducto) REFERENCES producto(idProducto)
)
```

## Verificación de Stock

### Para Platos:
- Se verifica `plato.stock` directamente
- Solo se pueden agregar platos si stock > 0
- El sistema NO descuenta automáticamente del stock

### Para Productos:
- Se verifica `producto.stock` directamente
- Solo se pueden agregar productos si stock > 0
- El sistema NO descuenta automáticamente del stock

**Nota**: El descuento de stock se debe hacer desde el módulo de Almacén cuando se reciben productos/insumos.

## Insumos y Cálculo de Stock (Futuro)

Para platos que se hacen a partir de insumos, el sistema debería:
1. Conocer la receta (composición de insumos) del plato
2. Calcular si hay suficiente insumo para hacer el plato
3. Validar stock de insumos antes de permitir agregar el plato

Este módulo aún no está implementado y se puede agregar en una próxima iteración.

## Datos de Prueba Generados

### Mesas (10 total)
- Mesas 1-3: Pequeñas (2 personas)
- Mesas 4-7: Medianas (4 personas)
- Mesas 8-10: Grandes (6 personas)

### Productos (15)
- 4 Pollos (Entero, Medio, Cuarto, Octavo)
- 4 Acompañamientos (Papas, Ensalada, Cremas, Arroz)
- 5 Bebidas (varios tamaños)
- 2 Postres (Helado 1L, ½L)

### Platos (12 Combos)
Todos los combos del menú con precios exactos del restaurante

### Usuarios Test
- **Admin**: email@admin.com
- **Mozo**: email@mozo.com
- **Cocinero**: email@cocinero.com
- **Jefe Almacén**: email@almacen.com

## Próximas Mejoras

1. **Vincular Recetas**: Asociar platos con sus insumos requeridos
2. **Cálculo de Stock de Insumos**: Validar ingredientes antes de permitir pedidos
3. **Impresión de Ordenes**: Enviar a cocina por ticket/impresora
4. **Tiempo de Preparación**: Registrar cuánto tarda cada item
5. **Bonificaciones**: Sistema de combos y descuentos
6. **Métodos de Pago**: Integración con sistema de pagos
7. **Reportes**: Análisis de ventas y tendencias

