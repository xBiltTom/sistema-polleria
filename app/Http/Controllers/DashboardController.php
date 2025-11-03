<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $empleado = $user->empleado;
        $tipoEmpleadoId = $empleado->idTipoEmpleado ?? null;

        $data = [
            'tipoEmpleado' => $tipoEmpleadoId,
            'empleado' => $empleado,
        ];

        return view('dashboard', $data);
    }
}
