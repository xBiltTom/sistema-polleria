@extends('layouts.app')

@section('title', 'Mesas - Registrar Pedido')

@section('content')
    <h2 class="mb-4">Registrar Pedido en Mesa</h2>
    
    <div class="row">
        @forelse(\App\Models\Mesa::all() as $mesa)
            <div class="col-md-3 mb-4">
                <div class="card {{ $mesa->estado === 'disponible' ? 'border-success' : ($mesa->estado === 'ocupada' ? 'border-danger' : 'border-warning') }}">
                    <div class="card-header {{ $mesa->estado === 'disponible' ? 'bg-success' : ($mesa->estado === 'ocupada' ? 'bg-danger' : 'bg-warning') }} text-white">
                        <h5 class="mb-0">Mesa {{ $mesa->nroMesa }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Capacidad:</strong> {{ $mesa->capacidadMesa }} personas</p>
                        <p><strong>Estado:</strong> <span class="badge {{ $mesa->estado === 'disponible' ? 'bg-success' : ($mesa->estado === 'ocupada' ? 'bg-danger' : 'bg-warning') }}">{{ ucfirst($mesa->estado) }}</span></p>
                        <p><strong>Descripción:</strong> {{ $mesa->descripcionMesa }}</p>
                        
                        @if($mesa->estado === 'disponible')
                            <button class="btn btn-primary btn-sm w-100">➕ Nuevo Pedido</button>
                        @else
                            <button class="btn btn-secondary btn-sm w-100" disabled>Mesa No Disponible</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No hay mesas registradas</div>
            </div>
        @endforelse
    </div>
@endsection
