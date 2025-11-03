@extends('layouts.app')

@section('title', 'Órdenes de Abastecimiento')

@section('content')
    <h2 class="mb-4">Órdenes de Abastecimiento</h2>
    
    @livewire('almacen.orden-abastecimiento-form')
@endsection

<a href="{{ route('admin.pagos') }}">...</a>
