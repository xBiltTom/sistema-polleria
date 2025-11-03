<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...existing code...
    ];

    protected $middlewareGroups = [
        // ...existing code...
    ];

    protected $routeMiddleware = [
        // ...existing code...
        'check.tipo_empleado' => \App\Http\Middleware\CheckTipoEmpleado::class,
    ];
}