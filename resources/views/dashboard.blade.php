@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard - Bienvenido {{ auth()->user()->name }}</h2>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Rol:</strong> {{ auth()->user()->empleado->tipoEmpleado->nombre ?? 'Usuario' }}
            </div>
        </div>
    </div>

    @if($tipoEmpleado == 1)
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5>Admin Dashboard</h5>
                        <p>Panel de administrador</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif($tipoEmpleado == 2)
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Mozo Dashboard</h5>
                        <p>Panel de mozo</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif($tipoEmpleado == 3)
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5>Cocinero Dashboard</h5>
                        <p>Panel de cocinero</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif($tipoEmpleado == 4)
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5>Almacén Dashboard</h5>
                        <p>Panel de jefe de almacén</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
