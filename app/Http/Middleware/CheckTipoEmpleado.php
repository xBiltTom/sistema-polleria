<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTipoEmpleado
{
    public function handle(Request $request, Closure $next, ...$tiposEmpleado)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect('/login');
        }

        $empleado = $user->empleado;
        
        if (!$empleado) {
            abort(403, 'No tienes perfil de empleado asignado');
        }

        $tipoEmpleadoUser = $empleado->idTipoEmpleado;

        if (!in_array($tipoEmpleadoUser, $tiposEmpleado)) {
            abort(403, 'No tienes permiso para acceder a esta sección');
        }

        return $next($request);
    }
}
