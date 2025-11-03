# 🚀 Guía Rápida de Instalación

## Instalación en 5 Pasos

### 1️⃣ Clonar e Instalar Dependencias
```bash
git clone https://github.com/xBiltTom/sistema-polleria.git
cd sistema-polleria
composer install
npm install
```

### 2️⃣ Configurar Base de Datos
```bash
cp .env.example .env
```

**Edita `.env`** y configura tu base de datos:
```env
DB_DATABASE=polleria_db
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

### 3️⃣ Crear Base de Datos
Crea una base de datos llamada `polleria_db` en MySQL

### 4️⃣ Migrar y Cargar Datos
```bash
php artisan key:generate
php artisan migrate --seed
```

Este comando creará:
- ✅ Todas las tablas
- ✅ 4 usuarios (admin, mozo, cocinero, almacén)
- ✅ 12 platos
- ✅ 15 productos
- ✅ 10 mesas
- ✅ Categorías e insumos

### 5️⃣ Compilar Assets e Iniciar
```bash
npm run build
php artisan serve
```

Abre tu navegador en: **http://127.0.0.1:8000**

---

## 🔑 Credenciales de Acceso

| Usuario | Email | Contraseña |
|---------|-------|------------|
| Admin | admin@polleria.com | admin123 |
| Mozo | mozo@polleria.com | mozo123 |
| Cocinero | cocinero@polleria.com | cocina123 |
| Almacén | almacen@polleria.com | almacen123 |

---

## ⚡ Comandos Útiles

```bash
# Reiniciar todo (BORRA DATOS)
php artisan migrate:fresh --seed

# Modo desarrollo con hot reload
npm run dev

# Limpiar caché
php artisan cache:clear && php artisan config:clear
```

---

## ❗ Problemas Comunes

### Error de migraciones
```bash
php artisan migrate:fresh --seed
```

### Error de permisos
```bash
chmod -R 775 storage bootstrap/cache
```

### Página en blanco
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

¡Listo! 🎉 Tu sistema está funcionando.
