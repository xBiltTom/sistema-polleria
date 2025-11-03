# 📋 GUÍA DE USO - SISTEMA DE PEDIDOS DE SALÓN

## 🍴 Para el MOZO

### Paso 1: Acceder al Sistema
1. Ir a `http://localhost:8000/mesas`
2. Se muestran todas las mesas disponibles

### Paso 2: Seleccionar Mesa
1. Ver estado de las mesas:
   - ✅ **VERDE** = Disponible (puede tomar pedido)
   - ❌ **ROJA** = Ocupada (no puede tomar pedido)
   - ⚠️ **AMARILLA** = Reservada (no puede tomar pedido)

2. Hacer clic en **"Nuevo Pedido"** en una mesa disponible

### Paso 3: Registrar Cliente
1. Ingresar **Nombre** del cliente (obligatorio)
2. Opcional: Ingresar **DNI**
3. Opcional: Ingresar **Celular**
4. Hacer clic en **"Continuar a Pedido"**

> **💡 Tip**: Si ingresa DNI repetido, el sistema busca cliente existente

### Paso 4: Tomar el Pedido

#### Buscar Platos:
1. En el campo "Buscar Platos", escribir mínimo 2 caracteres
2. Ejemplo: "combo", "primaveral", "pollo"
3. Se muestran máximo 5 opciones
4. Hacer clic en el plato para agregarlo
5. El campo se limpia automáticamente

#### Buscar Productos:
1. En el campo "Buscar Productos", escribir mínimo 2 caracteres
2. Ejemplo: "pollo", "bebida", "helado"
3. Se muestran máximo 5 opciones
4. Hacer clic en el producto para agregarlo

#### Modificar Cantidad:
1. En el resumen del pedido (lado derecho)
2. Usar botones **+** y **-** para aumentar/disminuir cantidad
3. El subtotal se calcula automáticamente

#### Agregar Observaciones:
1. En cada item, hay un campo "Obs..."
2. Ejemplos:
   - "Sin sal"
   - "Extra picante"
   - "Sin cebolla"
   - "A la orden"

#### Eliminar Item:
1. Hacer clic en **"Eliminar"** debajo del item

### Paso 5: Registrar Pedido
1. Verificar el **TOTAL** en la esquina superior derecha
2. Hacer clic en **"Registrar Pedido"**
3. El sistema:
   - ✅ Crea el pedido
   - ✅ Marca la mesa como OCUPADA
   - ✅ Envía a cocina

4. Volver a `/mesas` para tomar otro pedido

---

## 👨‍🍳 Para el COCINERO

### Acceder al Sistema
1. Ir a `http://localhost:8000/pedidos-cocina`
2. Se ven todos los pedidos del día

### Filtrar Pedidos
1. En la parte superior, ver botones:
   - **Pendientes** = Pedidos nuevos (por empezar)
   - **Preparando** = En proceso
   - **Completados** = Listos (esperando entrega)
   - **Entregados** = Finalizados

### Ver Detalles del Pedido
1. Hacer clic en cualquier pedido para expandir
2. Se muestran:
   - Número de mesa
   - Nombre del cliente
   - Mozo que lo registró
   - Hora del pedido
   - **Lista de items a preparar**

### Preparar Items

#### Para PLATOS:
1. Ver botón **"Iniciar Preparación"** (amarillo)
2. Hacer clic → el item pasa a estado "Preparando"
3. Cuando esté listo, clic en **"Marcar Completado"** (verde)
4. El item pasa a estado "Completado"

#### Para PRODUCTOS:
1. Ver botón **"Completado"** (verde)
2. Hacer clic inmediatamente (productos no necesitan preparación)

### Completar Pedido
1. Cuando **TODOS** los items estén completados
2. Hacer clic en **"Completar Pedido"** (botón verde)
3. El pedido pasa a estado "Completado"

### Entregar Pedido
1. El pedido está listo
2. Hacer clic en **"Marcar Entregado"** (botón morado)
3. La mesa queda libre para nuevo pedido

---

## 📊 Estados y Colores

### Estados de Pedido:
| Estado | Color | Significado |
|--------|-------|------------|
| Pendiente | 🟡 Amarillo | Acaba de llegar a cocina |
| Preparando | 🔵 Azul | Ya se está haciendo |
| Completado | 🟢 Verde | Listo, esperando que lo lleven |
| Entregado | 🟣 Púrpura | Terminado y entregado |

### Estados de Items:
| Estado | Acción |
|--------|--------|
| Pendiente | "Iniciar Preparación" |
| Preparando | "Marcar Completado" |
| Completado | ✅ Hecho |

---

## ✅ Verificaciones Importantes

- ✅ No se puede tomar pedido en mesa ocupada
- ✅ Solo se muestran platos/productos con stock > 0
- ✅ Total se calcula automáticamente
- ✅ Observaciones se guardan en el pedido
- ✅ Mesa se marca ocupada al registrar
- ✅ Todos los datos se sincronizan en tiempo real

---

## 🆘 Preguntas Frecuentes

**P: ¿Qué pasa si no ingreso un DNI?**
R: Se crea un cliente nuevo con solo el nombre. Si repito el nombre sin DNI, se crea otro cliente.

**P: ¿Puedo modificar un pedido ya registrado?**
R: No, se debe registrar nuevamente. En futuras versiones habrá edición de pedidos.

**P: ¿Qué pasa si se va la luz o falla el sistema?**
R: Los pedidos se guardan en la base de datos. Al reiniciar, aparecerán en su estado anterior.

**P: ¿Puedo tomar un pedido para llevar (delivery)?**
R: Actualmente el sistema es solo para salón. Delivery es una funcionalidad futura.

**P: ¿Cómo se descuentan los insumos?**
R: Automáticamente cuando se completa el pedido (funcionalidad próxima versión).

---

## 📞 Datos de Prueba

### Usuarios Disponibles:
- **Admin**: admin@example.com
- **Mozo**: mozo@example.com
- **Cocinero**: cocinero@example.com
- **Jefe Almacén**: almacen@example.com

Contraseña para todos: `password`

### Datos Pre-cargados:
- 10 Mesas (de 2, 4 y 6 personas)
- 15 Productos (pollos, acompañamientos, bebidas, postres)
- 12 Platos Combo (el menú completo de la pollería)
- 16 Insumos de cocina

---

**Versión**: 1.0 | Última actualización: 3 Nov 2025
**Soporte**: Sistema-Polleria-Dev

