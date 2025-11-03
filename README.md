# 🍗 Sistema de Gestión para Pollería

Sistema web completo para la gestión de una pollería, desarrollado con Laravel 12 y Livewire 3.

## 📋 Características

- **Gestión de Ventas en Sala**: Selección de mesas, registro de clientes, toma de pedidos
- **Gestión de Cocina**: Control de preparación de platos y pedidos
- **Sistema de Cobro**: Registro de pagos y liberación de mesas
- **Gestión de Almacén**: Control de inventario de productos e insumos
- **Gestión de Empleados**: Administración de usuarios y roles
- **Reportes**: Visualización de ventas y estadísticas

## 🚀 Instalación

### Requisitos Previos

- PHP >= 8.2
- Composer
- Node.js y NPM
- MySQL o MariaDB

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/xBiltTom/sistema-polleria.git
cd sistema-polleria
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar archivo de entorno**
```bash
cp .env.example .env
```

Edita el archivo `.env` y configura tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=polleria_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Generar clave de aplicación**
```bash
php artisan key:generate
```

6. **Crear la base de datos**
Crea una base de datos llamada `polleria_db` (o el nombre que pusiste en `.env`)

7. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

Esto creará todas las tablas y cargará los datos iniciales:
- ✅ Usuarios (Admin, Mozo, Cocinero, Almacén)
- ✅ 12 Platos (Combos de pollo a la brasa)
- ✅ 15 Productos (Pollos, acompañamientos, bebidas, postres)
- ✅ Categorías
- ✅ Mesas
- ✅ Insumos

8. **Compilar assets**
```bash
npm run build
```

9. **Iniciar el servidor de desarrollo**
```bash
php artisan serve
```

El sistema estará disponible en: `http://127.0.0.1:8000`

## 👥 Usuarios de Prueba

El sistema viene con 4 usuarios predefinidos:

| Rol | Email | Contraseña | Acceso |
|-----|-------|------------|--------|
| **Administrador** | admin@polleria.com | admin123 | Panel completo |
| **Mozo** | mozo@polleria.com | mozo123 | Ventas en sala, Cobro |
| **Cocinero** | cocinero@polleria.com | cocina123 | Gestión de pedidos |
| **Jefe Almacén** | almacen@polleria.com | almacen123 | Gestión de inventario |

## 🔧 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Reiniciar base de datos (CUIDADO: Borra todos los datos)
php artisan migrate:fresh --seed

# Ver rutas
php artisan route:list

# Modo desarrollo con hot reload
npm run dev
```

## 📱 Funcionalidades por Rol

### 🔑 Administrador
- Gestión completa de insumos, platos y productos
- Gestión de proveedores y empleados
- Configuración de mesas
- Reportes de ventas
- Control de inventario general

### 🍽️ Mozo
- **Venta en Sala**: Selección de mesa → Registro de cliente → Toma de pedido
- **Cobrar Pedidos**: Gestión de pagos y liberación de mesas
- Visualización de pedidos en curso

### 👨‍🍳 Cocinero
- Gestión de pedidos de cocina
- Control de preparación de platos
- Actualización de estados (Pendiente → Preparando → Completado)

### 📦 Jefe de Almacén
- Gestión de productos e insumos
- Control de inventario
- Órdenes de suministro
- Recepción de mercadería

## 🗂️ Estructura del Proyecto

```
sistema-polleria/
├── app/
│   ├── Livewire/          # Componentes Livewire
│   │   ├── Admin/         # Componentes del administrador
│   │   ├── Ventas/        # Venta en sala
│   │   ├── Mozo/          # Gestión de cobro
│   │   ├── Cocina/        # Gestión de pedidos
│   │   └── Almacen/       # Gestión de almacén
│   └── Models/            # Modelos Eloquent
├── database/
│   ├── migrations/        # Migraciones de base de datos
│   └── seeders/           # Datos iniciales
├── resources/
│   ├── views/             # Vistas Blade
│   └── js/                # JavaScript y Vue
└── routes/
    └── web.php            # Rutas de la aplicación
```

## 🔄 Flujo de Trabajo - Venta en Sala

1. **Mozo** selecciona mesa disponible
2. **Mozo** registra datos del cliente
3. **Mozo** toma el pedido (platos + productos)
4. Sistema marca mesa como "ocupada"
5. **Cocina** recibe pedido y prepara platos
6. **Cocina** marca platos como completados
7. **Mozo** entrega pedido al cliente
8. **Mozo** procesa el cobro
9. Sistema libera la mesa (disponible)

## 📊 Base de Datos

El sistema incluye los siguientes datos precargados:

### Platos (12 combos)
- COMBO PRIMAVERAL - S/ 34.99
- COMBO FAMILIAR - S/ 36.99
- COMBO GLOTÓN - S/ 41.99
- COMBO FENÓMENO - S/ 43.99
- COMBO YÁMBOLY - S/ 44.99
- COMBO DUO COOL - S/ 24.99
- COMBO RÓMPECABEZAS - S/ 39.99
- COMBO SOLTERO - S/ 29.99
- COMBO DUO - S/ 20.99
- COMBO EJECUTIVO - S/ 11.99
- COMBO LUCHITO - S/ 14.99
- COMBO JUNIOR - S/ 7.99

### Productos (15 items)
- Pollos (entero, medio, cuarto, octavo)
- Acompañamientos (papas, ensalada, cremas, chaufa)
- Bebidas (gaseosas de diferentes tamaños)
- Postres (helados)

### Categorías
- Categorías de Platos
- Categorías de Productos
- Categorías de Insumos

### Mesas
- 10 mesas con diferentes capacidades

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Livewire 3, TailwindCSS, Alpine.js
- **Base de Datos**: MySQL
- **Autenticación**: Laravel Breeze
- **Control de Acceso**: Middleware personalizado por tipo de empleado

## 📝 Notas Importantes

- Los **platos** se preparan bajo demanda (no requieren stock)
- Los **productos** sí requieren gestión de stock
- Las validaciones de stock solo aplican a productos
- El sistema controla automáticamente el estado de las mesas
- Los pedidos pasan por 4 estados: Pendiente → Preparando → Completado → Entregado

## 🐛 Solución de Problemas

### Error de migraciones
```bash
php artisan migrate:fresh --seed
```

### Error de permisos en storage
```bash
chmod -R 775 storage bootstrap/cache
```

### Problemas con node_modules
```bash
rm -rf node_modules package-lock.json
npm install
```

## 🤝 Contribuir

1. Fork el proyecto
2. Crea tu rama de características (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la Licencia MIT.

## 👨‍💻 Autor

**Adrian** - [@xBiltTom](https://github.com/xBiltTom)

---

⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub
