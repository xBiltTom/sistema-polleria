@extends('layouts.app')

@section('title', 'Gestión de Insumos')

@section('content')
    <h2 class="mb-4">Gestión de Insumos</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            @livewire('admin.categoria-insumo-form')
        </div>
        <div class="col-md-6">
            @livewire('admin.insumo-form')
        </div>
    </div>
@endsection
