@extends('layouts.app')

@section('title', 'Gestión de Platos')

@section('content')
    <h2 class="mb-4">Gestión de Platos</h2>
    
    <div class="row mb-4">
        <div class="col-md-6">
            @livewire('admin.categoria-plato-form')
        </div>
        <div class="col-md-6">
            @livewire('admin.plato-form')
        </div>
    </div>
@endsection
