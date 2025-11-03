<div class="container-fluid p-4">
    <!-- Alertas - MÁS VISIBLES -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x" style="z-index: 9999; width: 80%; margin-top: 10px;" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x" style="z-index: 9999; width: 80%; margin-top: 10px;" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">💳 Gestión de Pagos a Proveedores</h4>
        </div>
        <div class="card-body">
            @if(count($pagosPendientes) > 0)
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-info-circle"></i> 
                    <strong>{{ count($pagosPendientes) }} pagos pendientes</strong> por procesar
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">ID Pago</th>
                                <th style="width: 80px;">Orden</th>
                                <th>Proveedor</th>
                                <th style="width: 120px;" class="text-end">Monto Total</th>
                                <th style="width: 120px;">Fecha</th>
                                <th style="width: 140px;" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagosPendientes as $pago)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">#{{ $pago->idPagoOrden }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $pago->idOrden ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $pago->razonSocial ?? 'Sin proveedor' }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">S/. {{ number_format($pago->montoTotal ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($pago->fechaCreacion)->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm" 
                                                wire:click="abrirFormulario({{ $pago->idPagoOrden }})">
                                            ✔️ Pagar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> 
                    <strong>¡Excelente!</strong> No hay pagos pendientes.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($showForm)
                <div class="modal-backdrop fade show" style="display: block;"></div>
                <div class="modal fade show" tabindex="-1" id="pagoModal" style="display: block;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">💰 Registrar Pago #{{ $idPago }}</h5>
                                <button type="button" class="btn-close btn-close-white" wire:click="cancelar()"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><strong>Tipo de Pago *</strong></label>
                                        <select class="form-control" wire:model="idTipoPago">
                                            <option value="">-- Seleccionar --</option>
                                            <option value="1">💵 Efectivo</option>
                                            <option value="2">🏦 Transferencia Bancaria</option>
                                            <option value="3">📋 Cheque</option>
                                            <option value="4">💳 Tarjeta de Crédito</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><strong>Fecha de Pago *</strong></label>
                                        <input type="date" class="form-control" wire:model="fechaPago">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><strong>Observaciones</strong></label>
                                    <textarea class="form-control" wire:model="observaciones" rows="3" placeholder="Agregar observaciones del pago..."></textarea>
                                </div>

                                <div class="alert alert-info">
                                    <strong>Recuerda:</strong> Verifica que todos los datos sean correctos antes de confirmar el pago.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="cancelar()">
                                    ❌ Cancelar
                                </button>
                                <button type="button" class="btn btn-success btn-lg" wire:click="registrarPago()">
                                    ✔️ Confirmar Pago
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Escuchar cambios de Livewire
        document.addEventListener('livewire:updated', (event) => {
            // Si hay un modal abierto y ahora hay un mensaje, cerrar el modal
            const modal = document.getElementById('pagoModal');
            const alertaExito = document.querySelector('.alert-success:not(.position-absolute)');
            const alertaError = document.querySelector('.alert-danger:not(.position-absolute)');
            
            if (modal && (alertaExito || alertaError)) {
                setTimeout(() => {
                    modal.style.display = 'none';
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.style.display = 'none';
                    }
                }, 300);
            }
        });
    </script>
</div>
