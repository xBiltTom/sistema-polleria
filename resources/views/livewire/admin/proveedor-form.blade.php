<div class="container-fluid" wire:poll.5s>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Gestión de Proveedores</h5>
            <button class="btn btn-primary btn-sm" wire:click="openForm">
                ➕ Nuevo Proveedor
            </button>
        </div>
        <div class="card-body">
            @if($showForm)
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h6>{{ $editingId ? 'Editar' : 'Crear' }} Proveedor</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">Datos del Proveedor</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Razón Social *</label>
                                    <input type="text" class="form-control" wire:model="razonSocial" placeholder="Nombre de la empresa">
                                    @error('razonSocial') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>RUC *</label>
                                    <input type="text" class="form-control" wire:model="ruc" placeholder="20123456789">
                                    @error('ruc') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Teléfono</label>
                                    <input type="text" class="form-control" wire:model="telefono" placeholder="01-2345678">
                                    @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Email</label>
                                    <input type="email" class="form-control" wire:model="email" placeholder="contacto@proveedor.com">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Dirección</label>
                                    <input type="text" class="form-control" wire:model="direccion" placeholder="Dirección del proveedor">
                                    @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Número de Cuenta</label>
                                    <input type="text" class="form-control" wire:model="nroCuenta" placeholder="Cuenta bancaria">
                                    @error('nroCuenta') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <h6 class="mb-3">Datos del Contacto</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" wire:model="nombroContacto" placeholder="Nombre del contacto">
                                    @error('nombroContacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Apellido</label>
                                    <input type="text" class="form-control" wire:model="apellidoContacto" placeholder="Apellido del contacto">
                                    @error('apellidoContacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>DNI</label>
                                    <input type="text" class="form-control" wire:model="dniContacto" placeholder="12345678" maxlength="9">
                                    @error('dniContacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Cargo</label>
                                    <input type="text" class="form-control" wire:model="cargoContacto" placeholder="Gerente, Vendedor, etc.">
                                    @error('cargoContacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Email Contacto</label>
                                    <input type="email" class="form-control" wire:model="emailContacto" placeholder="contacto@mail.com">
                                    @error('emailContacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Teléfono Contacto</label>
                                    <input type="text" class="form-control" wire:model="telefonoContacto" placeholder="987654321" maxlength="9">
                                    @error('telefonoContacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-success btn-sm" wire:click="save">💾 Guardar</button>
                            <button class="btn btn-secondary btn-sm" wire:click="resetForm; $set('showForm', false)">❌ Cancelar</button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Razón Social</th>
                            <th>RUC</th>
                            <th>Contacto</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedores as $prov)
                            <tr>
                                <td><strong>#{{ $prov['idProveedor'] }}</strong></td>
                                <td>{{ $prov['razonSocial'] }}</td>
                                <td>{{ $prov['ruc'] }}</td>
                                <td>
                                    @if($prov['contacto'])
                                        {{ $prov['contacto']['nombroContacto'] }} {{ $prov['contacto']['apellidoContacto'] }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $prov['emial'] ?? '-' }}</td>
                                <td>{{ $prov['telefono'] ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-xs" wire:click="edit({{ $prov['idProveedor'] }})">✏️</button>
                                    <button class="btn btn-danger btn-xs" 
                                            onclick="confirm('¿Eliminar?') && @this.call('delete', {{ $prov['idProveedor'] }})">🗑️
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay proveedores registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
