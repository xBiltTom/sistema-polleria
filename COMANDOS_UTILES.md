# 🔍 Comandos de Verificación y Debugging

## Verificar Estado de Migraciones

```bash
# Ver todas las migraciones ejecutadas
php artisan migrate:status

# Ver migraciones pendientes
php artisan migrate:status --pending

# Ver la última migración
php artisan migrate:status | grep "Y"
```

## Verificar Base de Datos

```bash
# Conectar a base de datos
mysql -u root -p sistema_polleria

# Ver columnas de pedido
DESCRIBE pedido;

# Ver columnas de preparacion_plato
DESCRIBE preparacion_plato;

# Ver pedidos entregados sin pagar
SELECT idPedido, estadoPedido, estadoPago FROM pedido 
WHERE estadoPedido = 'entregado' AND estadoPago = 'pendiente';

# Ver mesas ocupadas
SELECT idMesa, nroMesa, estado FROM mesa WHERE estado = 'ocupada';

# Ver preparaciones en progreso
SELECT * FROM preparacion_plato 
WHERE fechaInicio IS NOT NULL AND fechaFin IS NULL;
```

## Limpiar Cache y Compilados

```bash
# Limpiar todo
php artisan optimize:clear

# O por separado
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan compiled:clear

# Regenerar autoload
composer dump-autoload

# Recompilar
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Logs y Errores

```bash
# Ver últimos errores
tail -f storage/logs/laravel.log

# Ver errores de hoy
tail -50 storage/logs/laravel.log

# Buscar errores específicos
grep "ERROR" storage/logs/laravel.log
grep "Exception" storage/logs/laravel.log

# Limpiar logs
rm storage/logs/laravel.log
```

## Verificar Integridad de Datos

```php
// En tinker o script
php artisan tinker

// Verificar pedidos
> Pedido::count()
> Pedido::where('estadoPago', 'pagado')->count()
> Pedido::where('estadoPedido', 'entregado')->where('estadoPago', 'pendiente')->count()

// Verificar mesas
> Mesa::where('estado', 'disponible')->count()
> Mesa::where('estado', 'ocupada')->count()

// Verificar preparaciones
> PreparacionPlato::whereNull('fechaFin')->count()
```

## Verificar Componentes Livewire

```bash
# Listar componentes
php artisan livewire:list

# Crear componente
php artisan livewire:make NombreComponente

# Ver estado de componentes
php artisan make:livewire

# Regenerar componentes
php artisan livewire:copy
```

## Rutas Activas

```bash
# Ver todas las rutas
php artisan route:list

# Ver rutas específicas
php artisan route:list | grep mozo
php artisan route:list | grep cocina
php artisan route:list | grep cobrar

# Ver detalles de ruta
php artisan route:show mozo.pedido-salon
```

## Usuarios de Prueba

```php
// En tinker
php artisan tinker

// Ver usuarios
> User::all()

// Ver empleados
> Empleado::with('tipoEmpleado')->get()

// Crear usuario de prueba
> User::create([
    'name' => 'Test Mozo',
    'email' => 'mozo@test.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
])

// Asignar tipo empleado
> $user = User::find(1)
> $user->empleado()->create(['idTipoEmpleado' => 2])
```

## Testing de Flujo Completo

```bash
# Terminal 1: Ejecutar servidor
php artisan serve

# Terminal 2: Ver logs en vivo
tail -f storage/logs/laravel.log

# Luego en navegador:
# 1. Ir a /login como mozo
# 2. Crear pedido en /pedido-salon
# 3. Ver en cocina /pedidos-cocina
# 4. Cobrar en /cobrar-pedido
```

## Validar Integridad Referencial

```sql
-- Verificar FK de pedidos sin cliente
SELECT * FROM pedido WHERE idCliente NOT IN (SELECT idCliente FROM cliente);

-- Verificar FK de pedidos sin mesa
SELECT * FROM pedido WHERE idMesa NOT IN (SELECT idMesa FROM mesa);

-- Verificar detalles sin pedido
SELECT * FROM detalle_pedido WHERE idPedido NOT IN (SELECT idPedido FROM pedido);

-- Verificar detalles sin plato ni producto
SELECT * FROM detalle_pedido WHERE idPlato IS NULL AND idProducto IS NULL;
```

## Verificar Búsqueda Reactiva

```php
// En tinker
php artisan tinker

// Ver platos con stock
> Plato::where('stock', '>', 0)->get()

// Ver productos con stock
> Producto::where('stock', '>', 0)->get()

// Buscar por nombre
> Plato::where('nombrePlato', 'LIKE', '%doble%')->get()
> Producto::where('nombre', 'LIKE', '%gaseosa%')->get()
```

## Performance Check

```bash
# Ver queries lentas (habilitar query log)
php artisan tinker

> DB::enableQueryLog()
> // Ejecutar operación
> DB::getQueryLog()

# Ver tabla de pedidos tamaño
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.tables 
WHERE table_schema = 'sistema_polleria'
ORDER BY size_mb DESC;
```

## Resetear Base de Datos (CUIDADO)

```bash
# Borrar TODO y resetear
php artisan migrate:reset

# Ejecutar migraciones nuevamente
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# O todo junto
php artisan migrate:refresh --seed
```

## Verificar Permisos

```bash
# Storage debe ser escribible
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Ver permisos
ls -la storage/
ls -la bootstrap/

# Cambiar dueño (si es necesario)
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/
```

## Monitoreo en Vivo

```bash
# Ver eventos de BD en tiempo real
watch -n 1 "mysql -u root -p sistema_polleria -e 'SELECT COUNT(*) as pedidos_activos FROM pedido WHERE estadoPedido != \"entregado\";'"

# Ver conexiones
mysql -u root -p sistema_polleria -e "SHOW PROCESSLIST;"

# Ver tabla de InnoDB
mysql -u root -p sistema_polleria -e "SHOW ENGINE INNODB STATUS\G"
```

## Debugging de Componentes Livewire

```php
// En componente, agregar logs
public function cambiarEstadoDetalle($idDetalle, $nuevoEstado)
{
    \Log::info("Cambiar estado detalle", [
        'idDetalle' => $idDetalle,
        'nuevoEstado' => $nuevoEstado,
    ]);
    
    // ... código ...
}

// Revisar logs
tail -f storage/logs/laravel.log | grep "Cambiar estado"
```

## Verificar Sistema Completo

```bash
#!/bin/bash
# Script de verificación completa

echo "=== Verificación del Sistema ==="
echo ""
echo "1. Estado de migraciones:"
php artisan migrate:status --pending

echo ""
echo "2. Rutas activas (mozo):"
php artisan route:list | grep "mozo\|cobrar"

echo ""
echo "3. Componentes Livewire:"
php artisan livewire:list

echo ""
echo "4. Usuarios de prueba:"
php artisan tinker --execute="echo User::count() . ' usuarios en el sistema';"

echo ""
echo "5. Pedidos activos:"
php artisan tinker --execute="echo Pedido::count() . ' pedidos totales, ' . Pedido::where('estadoPago', 'pendiente')->count() . ' sin pagar';"

echo ""
echo "=== Verificación Completada ==="
```

## Comandos Útiles Rápidos

```bash
# Servir aplicación
php artisan serve

# Optimizar (produción)
php artisan optimize

# Clear optimizaciones
php artisan optimize:clear

# Ejecutar migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Revertir todo y re-migrar
php artisan migrate:refresh

# Con seeders
php artisan migrate:refresh --seed

# Ver Estado
php artisan tinker

# Crear admin rápidamente
php artisan tinker
> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'email_verified_at' => now()])

# Queue work (si aplica)
php artisan queue:work

# Schedule run (si aplica)
php artisan schedule:run
```

## Verificación Post-Corrección

```bash
# Ejecutar esta serie para validar los bugs están corregidos:

echo "=== VERIFICACIÓN DE BUGS CORREGIDOS ==="

echo "1. Verificar migración preparacion_plato:"
mysql -u root -p sistema_polleria -e "DESCRIBE preparacion_plato;" | grep -E "fechaInicio|fechaFin"

echo "2. Verificar columnas de pago en pedido:"
mysql -u root -p sistema_polleria -e "DESCRIBE pedido;" | grep -E "estadoPago|totalPedido|fechaPago"

echo "3. Verificar idPlato nullable:"
mysql -u root -p sistema_polleria -e "DESCRIBE detalle_pedido;" | grep idPlato

echo "4. Ver componentes actualizado:"
grep -n "cargarPedidosEntregados" app/Livewire/Mozo/CobrarPedido.php

echo "5. Ver fix en preparacion:"
grep -n "idPedido" app/Livewire/Cocina/GestionPedidos.php

echo ""
echo "=== TODO LISTO ==="
```

