@extends('layouts.app')

@section('title', 'Gestión de Mesas')

@section('content')
    <h2 class="mb-4">Gestión de Mesas</h2>
    
    @livewire('admin.mesa-form')
@endsection
