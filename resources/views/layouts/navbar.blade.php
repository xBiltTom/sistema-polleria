<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">
            Bienvenido, {{ auth()->user()->name }} 
            <span class="badge bg-info ms-2">
                {{ auth()->user()->empleado->tipoEmpleado->nombre ?? 'Usuario' }}
            </span>
        </span>
        
        <div class="ms-auto">
            <span class="text-muted me-3">
                {{ now()->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>
</nav>
