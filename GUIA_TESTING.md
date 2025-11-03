# 🧪 Guía de Testing - Sistema de Pedidos de Salón

## Credenciales de Prueba

```
Admin (Tipo 1):
- Email: admin@test.com
- Contraseña: password
- Acceso: Todos los módulos

Mozo (Tipo 2):
- Email: mozo@test.com
- Contraseña: password
- Acceso: /mesas, /pedido-salon, /cobrar-pedido

Cocinero (Tipo 3):
- Email: cocinero@test.com
- Contraseña: password
- Acceso: /pedidos-cocina

Jefe Almacén (Tipo 4):
- Email: almacen@test.com
- Contraseña: password
- Acceso: Gestión de almacén
```

---

## Test 1: Crear Pedido Completo (Platos + Productos)

### Pre-requisitos
- Mesas disponibles
- Platos con stock
- Productos con stock

### Pasos

**1. Login como Mozo**
```
URL: http://127.0.0.1:8000/login
Email: mozo@test.com
Password: password
→ Redirect a /dashboard
```

**2. Ir a Tomar Pedido**
```
Sidebar: 🪑 Mesas
URL: /mesas
Debe mostrar:
- Grid de mesas (2-4 columnas responsive)
- Mesas con estado "disponible" en verde
- Mesas con estado "ocupada" en rojo
```

**3. Seleccionar Mesa**
```
Click en "Mesa 1" (si está disponible)
→ Modal o transición a "Registrar Cliente"
```

**4. Registrar Cliente**
```
Campo: Nombre *
Ingreso: "Test Cliente 001"

Campo: DNI
Ingreso: "12345678" (opcional)

Campo: Celular
Ingreso: "987654321" (opcional)

Click: "Continuar"
→ Muestra "PASO 3: Tomar Pedido"
```

**5. Buscar y Agregar Platos**
```
Campo: "Buscar platos..."
Ingreso: Mínimo 2 caracteres, ej: "do"

Esperar:
- Resultados: máximo 5 items
- Solo items con stock > 0
- Mostrar nombre, precio, stock

Click: "Doble pechuga" (o similar)
Debe agregarse a "Resumen del Pedido":
- Cantidad: 1
- Precio: S/ 18.00
- Subtotal: S/ 18.00

Repetir: Agregar otro plato
- Click: "Cuarto de pollo"
- Cantidad: 2
- Precio: S/ 12.00 c/u
- Subtotal: S/ 24.00
```

**6. Buscar y Agregar Productos**
```
Campo: "Buscar productos..."
Ingreso: "gas" (para gaseosa)

Click: "Gaseosa 2L"
Debe agregarse:
- Cantidad: 1
- Precio: S/ 5.00
- Subtotal: S/ 5.00

Click: Agregar otra gaseosa (+)
- Cantidad: 2
- Subtotal: S/ 10.00
```

**7. Verificar Resumen**
```
PLATOS (Azul):
- Doble pechuga x1: S/ 18.00
- Cuarto x2: S/ 24.00

PRODUCTOS (Verde):
- Gaseosa 2L x2: S/ 10.00

Total: S/ 52.00 ✓

Agregar observación en algún item
Click en campo "Obs..." en item
Ingreso: "Sin picante" (ejemplo)
```

**8. Registrar Pedido**
```
Click: "💚 Registrar Pedido"
Debe mostrar:
- Mensaje de éxito
- Regreso a seleccionar mesa
- Mesa 1 debe estar "ocupada" (roja)
```

**✅ Test 1 Completado**

---

## Test 2: Preparación en Cocina

### Pre-requisitos
- Pedido creado (Test 1 completado)

### Pasos

**1. Login como Cocinero**
```
URL: /logout
Email: cocinero@test.com
Password: password
→ Dashboard
```

**2. Ir a Gestión de Pedidos**
```
Sidebar: 👨‍🍳 Pedidos Cocina
URL: /pedidos-cocina
Debe mostrar:
- Filtro de estados (Pendientes, Preparando, Completados, Entregados)
- Lista de pedidos en estado "Pendiente"
```

**3. Ver Pedido Creado**
```
Debe mostrar algo como:
"Pedido #1 | Mesa 1 | Test Cliente 001 | 14:30"
Estado: 🟨 Pendiente

Click: en el pedido
→ Se expande mostrando detalles
```

**4. Iniciar Preparación de Platos**
```
En la sección de PLATOS (azul):
- Doble pechuga x1: 🟨 Pendiente
  Click: "Iniciar Preparación"
  → Estado cambia a 🔵 Preparando

- Cuarto x2: 🟨 Pendiente
  Click: "Iniciar Preparación"
  → Estado cambia a 🔵 Preparando
```

**5. Marcar Platos Completados**
```
Esperar unos segundos (simular preparación)

En PLATOS (azul):
- Doble pechuga: 🔵 Preparando
  Click: "Marcar Completado"
  → Estado cambia a 🟢 Completado

- Cuarto: 🔵 Preparando
  Click: "Marcar Completado"
  → Estado cambia a 🟢 Completado
```

**6. Marcar Productos Completados**
```
En la sección de PRODUCTOS (verde):
- Gaseosa 2L x2: 🟨 Pendiente
  Click: "Completado"
  → Estado cambia a 🟢 Completado
```

**7. Completar Pedido**
```
En botones de pedido:
Click: "Completar Pedido"
→ Todos los items deben mostrar 🟢 Completado
→ Pedido estado cambia a 🟢 Completado
```

**8. Marcar Entregado**
```
Click: "Marcar Entregado"
→ Pedido estado cambia a 🟣 Entregado
→ Pedido desaparece de "Pendientes"
```

**9. Verificar Cambio de Estado**
```
Cambiar filtro a "Entregados"
→ Debe mostrar "Pedido #1" en estado 🟣 Entregado
```

**✅ Test 2 Completado**

---

## Test 3: Cobro y Liberación de Mesa

### Pre-requisitos
- Pedido entregado (Test 2 completado)

### Pasos

**1. Login como Mozo**
```
URL: /logout
Email: mozo@test.com
Password: password
→ Dashboard
```

**2. Ir a Cobrar Pedidos**
```
Sidebar: 💰 Cobrar Pedidos
URL: /cobrar-pedido
Debe mostrar:
- Lista de pedidos con estadoPedido="entregado" y estadoPago="pendiente"
- Pedido #1: "Mesa 1 - Test Cliente 001 | S/ 52.00 | 14:30"
```

**3. Seleccionar Pedido**
```
Click: en el pedido
→ Se expande en columna derecha mostrando:
  - Mesa: 1
  - Cliente: Test Cliente 001
  - Hora: 14:30
  - Items con precios
  - Total: S/ 52.00
```

**4. Seleccionar Método de Pago**
```
Dropdown: "Método de Pago"
Seleccionar: "💵 Efectivo"
→ Debe mostrar campo "Monto Recibido"
```

**5. Ingreso de Monto Recibido**
```
Campo: "Monto Recibido"
Ingreso: "60.00"

Debe calcularse automáticamente:
- Total: S/ 52.00
- Recibido: S/ 60.00
- Cambio: S/ 8.00 ✓
```

**6. Realizar Cobro**
```
Click: "💰 Realizar Cobro y Liberar Mesa"
Debe mostrar mensaje:
"Cobro realizado: S/ 52.00 | Cambio: S/ 8.00 | Mesa 1 liberada"

Pedido desaparece de la lista
```

**7. Verificar Mesa Liberada**
```
Ir a: /mesas
Sidebar: 🪑 Mesas
→ Mesa 1 debe estar "disponible" (verde)
→ Puede ser seleccionada nuevamente
```

**✅ Test 3 Completado**

---

## Test 4: Método de Pago Alternativo (Tarjeta)

### Pasos

**1. Crear nuevo Pedido**
```
Repetir Test 1 con:
- Mesa 2
- Cliente: "Juan Rodríguez"
- 1x Doble pechuga (S/ 18.00)
- Total: S/ 18.00
```

**2. Preparar en Cocina**
```
Repetir Test 2 (simplificado):
- Iniciar preparación de platos
- Marcar completados
- Completar pedido
- Marcar entregado
```

**3. Cobrar con Tarjeta**
```
URL: /cobrar-pedido
Click: Pedido #2
Selector: Cambiar a "💳 Tarjeta"
→ Debe desaparecer campo "Monto Recibido"
→ Debe desaparecer cálculo de cambio

Click: "💰 Realizar Cobro"
→ Mensaje de éxito sin mostrar cambio
→ Mesa 2 liberada ✓
```

**✅ Test 4 Completado**

---

## Test 5: Validación de Monto Insuficiente

### Pasos

**1. Crear Pedido**
```
Total: S/ 50.00
```

**2. Cobrar con Efectivo - Monto Insuficiente**
```
URL: /cobrar-pedido
Método: Efectivo
Monto Recibido: 40.00 (menos del total)
→ Cambio muestra: -S/ 10.00

Click: "Realizar Cobro"
→ Debe mostrar error: "Monto insuficiente"
→ Pedido NO se marca como pagado
→ Mesa NO se libera
```

**3. Corregir Monto**
```
Monto Recibido: 50.00 (exacto)
Cambio: S/ 0.00

Click: "Realizar Cobro"
→ Éxito ✓
```

**✅ Test 5 Completado**

---

## Test 6: Solo Productos (Sin Platos)

### Pasos

**1. Crear Pedido con Solo Productos**
```
Mesa: 3
Cliente: "Ana García"
- 2x Cerveza (S/ 6.00 c/u)
- 1x Helado (S/ 3.00)
Total: S/ 15.00
```

**2. Verificar en Cocina**
```
URL: /pedidos-cocina
→ Pedido debe mostrar:
  - Sin sección de PLATOS
  - Solo sección PRODUCTOS
  - Botón "Completado" directo (sin preparación)
```

**3. Marcar Completado**
```
Click: "Completado" en cada producto
→ Estados cambian a 🟢
Click: "Completar Pedido"
Click: "Marcar Entregado"
```

**4. Cobrar**
```
URL: /cobrar-pedido
Total: S/ 15.00
Método: Transferencia
Click: "Realizar Cobro"
→ Éxito sin mostrar cambio
→ Mesa 3 liberada ✓
```

**✅ Test 6 Completado**

---

## Test 7: Responsividad (Mobile)

### En navegador de escritorio

**1. Abrir DevTools (F12)**
```
Click: Device Toggle (Ctrl+Shift+M)
```

**2. Probar Vista Mobile**
```
Resolver:
- /mesas: Grid de 1 columna
- /pedido-salon: Stack de 1 columna
- /cobrar-pedido: Stack responsivo
- /pedidos-cocina: Legible sin scroll horizontal
```

**3. Probar Vista Tablet (768px)**
```
- /mesas: Grid de 2 columnas
- /pedido-salon: 2 columnas
```

**4. Probar Vista Desktop (1024px)**
```
- /mesas: Grid de 3-4 columnas
- /pedido-salon: 2-1 layout
```

**✅ Test 7 Completado**

---

## Test 8: Performance y Carga

### Pasos

**1. Crear 10 Pedidos**
```
Repetir Test 1 con diferentes mesas
Tomar 10 pedidos

URL: /pedidos-cocina
Debe cargar sin delays
Expandir varios pedidos
```

**2. Buscar Platos**
```
URL: /pedido-salon
Buscar: "llo" (para "pollo")
→ Resultados en < 500ms
→ Máximo 5 resultados
```

**3. Cambiar Filtros en Cocina**
```
URL: /pedidos-cocina
Cambiar entre filtros
→ Carga rápida (< 1s)
```

**✅ Test 8 Completado**

---

## Test 9: Ediciones y Cambios

### Cambio de Cantidad

**1. En Pedido**
```
Agregar plato
Click: "-" button
→ Cantidad disminuye
→ Subtotal actualiza
→ Total recalcula

Click: "+" button
→ Cantidad aumenta
→ Actualización reactiva
```

**2. Eliminar Item**
```
Click: "Eliminar" en item
→ Item desaparece
→ Total recalcula
→ Si total = 0, mostrar "sin items"
```

**✅ Test 9 Completado**

---

## Checklist de Validación Final

- [ ] Mesas se marcan como ocupadas al registrar pedido
- [ ] Mesas se liberan al cobrar
- [ ] Total se calcula correctamente
- [ ] Cambio se calcula correctamente (efectivo)
- [ ] Estados transicionan correctamente
- [ ] No hay errores en consola
- [ ] Interfaces son responsivas
- [ ] Botones están activos/inactivos según corresponda
- [ ] Mensajes de éxito/error se muestran
- [ ] Datos persisten en BD
- [ ] Relaciones entre modelos funcionan
- [ ] Búsqueda es reactiva

---

## Posibles Errores y Soluciones

### Error: "Field 'idPlato' doesn't have a default value"
✅ **Solución**: Migración 2025_11_03_000007 hace idPlato nullable
```bash
php artisan migrate --force
```

### Error: "Route [pedidos.cocina] not defined"
✅ **Solución**: Cambiar a `route('cocina.pedidos')`
```blade
<a href="{{ route('cocina.pedidos') }}">
```

### Error: Botón no responde
✅ **Solución**: Verificar wire:click tiene método correcto
```blade
wire:click="cambiarEstadoDetalle({{ $detalle->idDetalle }}, 'preparando')"
```

### Error: Total no actualiza
✅ **Solución**: Llamar a `recalcularTotal()` después de cambios
```php
$this->recalcularTotal();
```

---

## Comandos Útiles para Testing

```bash
# Limpiar datos
php artisan migrate:refresh --seed

# Ejecutar solo migraciones nuevas
php artisan migrate --force

# Ver estado de BD
php artisan tinker
> Pedido::count()
> Mesa::all()

# Limpiar cache
php artisan optimize:clear

# Modo desarrollo
php artisan serve

# Ver logs
tail -f storage/logs/laravel.log
```

