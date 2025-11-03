@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content')
    <h2 class="mb-4">Gestión de Productos</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            @livewire('admin.categoria-producto-form')
        </div>
        <div class="col-md-6">
            @livewire('admin.producto-form')
        </div>
    </div>
@endsection
