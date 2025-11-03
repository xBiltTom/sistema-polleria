@extends('layouts.app')

@section('title', 'Gestión de Proveedores')

@section('content')
    <h2 class="mb-4">Gestión de Proveedores</h2>
    
    @livewire('admin.proveedor-form')
@endsection
