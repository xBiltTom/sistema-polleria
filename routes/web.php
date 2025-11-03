<?php

use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckTipoEmpleado;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // Admin Routes
    Route::middleware(CheckTipoEmpleado::class . ':1')->group(function () {
        Route::get('/admin/insumos', fn() => view('admin.insumos'))->name('insumos.index');
        Route::get('/admin/platos', fn() => view('admin.platos'))->name('platos.index');
        Route::get('/admin/productos', fn() => view('admin.productos'))->name('productos.index');
        Route::get('/admin/proveedores', fn() => view('admin.proveedores'))->name('proveedores.index');
        Route::get('/admin/mesas', fn() => view('admin.mesas'))->name('mesas.index');
        Route::get('/admin/empleados', fn() => view('admin.empleados'))->name('empleados.index');
        Route::get('/admin/ventas', fn() => view('ventas.index'))->name('ventas.index');
        Route::get('/admin/comprobantes', fn() => view('comprobantes.index'))->name('comprobantes.index');
        Route::get('/admin/almacen', fn() => view('almacen.index'))->name('almacen.index');
        Route::get('/admin/reportes', fn() => view('reportes.index'))->name('reportes.index');
        Route::get('/admin/pagos', fn() => view('admin.pagos'))->name('admin.pagos');
        Route::get('/admin/inventario', fn() => view('admin.inventario'))->name('admin.inventario');
    });

    // Mozo Routes
    Route::middleware(CheckTipoEmpleado::class . ':2')->group(function () {
        Route::get('/mesas', fn() => view('mozo.mesas'))->name('mozo.mesas');
        Route::get('/ventas-sala', fn() => view('ventas.sala'))->name('ventas.sala');
        Route::get('/ventas-delivery', fn() => view('ventas.delivery'))->name('ventas.delivery');
    });

    // Cocinero Routes
    Route::middleware(CheckTipoEmpleado::class . ':3')->group(function () {
        Route::get('/pedidos-cocina', fn() => view('pedidos.cocina'))->name('pedidos.cocina');
        Route::get('/preparacion', fn() => view('preparacion.index'))->name('preparacion.index');
    });

    // Jefe Almacén Routes
    Route::middleware(CheckTipoEmpleado::class . ':4')->group(function () {
        Route::get('/almacen-productos', fn() => view('almacen.productos'))->name('almacen.productos');
        Route::get('/almacen-inventario', fn() => view('almacen.inventario'))->name('almacen.inventario');
        Route::get('/almacen-compras', fn() => view('almacen.compras'))->name('almacen.compras');
        Route::get('/almacen-ordenes', fn() => view('almacen.ordenes'))->name('almacen.ordenes');
        Route::get('/almacen-recepcion', fn() => view('almacen.recepcion'))->name('almacen.recepcion');
    });

    // Profile Routes
    Route::get('/profile', fn() => view('profile.edit'))->name('profile.edit');
});

require __DIR__.'/auth.php';
