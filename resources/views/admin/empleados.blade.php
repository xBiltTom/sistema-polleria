@extends('layouts.app')

@section('title', 'Gestión de Empleados')

@section('content')
    <h2 class="mb-4">Gestión de Empleados</h2>
    
    @livewire('admin.empleado-form')
@endsection
