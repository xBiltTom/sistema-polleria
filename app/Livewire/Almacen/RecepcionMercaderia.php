<?php

namespace App\Livewire\Almacen;

use App\Models\OrdenAbastecimiento;
use App\Models\Producto;
use App\Models\Insumo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RecepcionMercaderia extends Component
{
    public $ordenes = [];
    public $productos = [];
    public $insumos = [];
    public $showForm = false;
    public $mostrarResumen = false;
    public $resumenRecepcion = [];

    public $idOrden = '';
    public $tabActiva = 'productos';
    public $detallesProductos = [];
    public $detallesInsumos = [];

    public $totalProductos = 0;
    public $totalInsumos = 0;
    public $subtotal = 0;
    public $igv = 0;
    public $totalPagar = 0;

    public function mount()
    {
        $this->loadOrdenes();
        $this->loadProductos();
        $this->loadInsumos();
    }

    public function loadOrdenes()
    {
        try {
            $this->ordenes = OrdenAbastecimiento::with(['proveedor', 'empleado'])
                ->where('estado', '!=', 'completada')
                ->orderBy('idOrden', 'DESC')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            \Log::error('Error cargando órdenes: ' . $e->getMessage());
            $this->ordenes = [];
        }
    }

    public function loadProductos()
    {
        try {
            $this->productos = Producto::all()->toArray();
        } catch (\Exception $e) {
            \Log::error('Error cargando productos: ' . $e->getMessage());
            $this->productos = [];
        }
    }

    public function loadInsumos()
    {
        try {
            $this->insumos = Insumo::all()->toArray();
        } catch (\Exception $e) {
            \Log::error('Error cargando insumos: ' . $e->getMessage());
            $this->insumos = [];
        }
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->idOrden = '';
        $this->detallesProductos = [];
        $this->detallesInsumos = [];
        $this->tabActiva = 'productos';
        $this->mostrarResumen = false;
        $this->totalProductos = 0;
        $this->totalInsumos = 0;
        $this->subtotal = 0;
        $this->igv = 0;
        $this->totalPagar = 0;
        $this->resumenRecepcion = [
            'productos' => [],
            'insumos' => []
        ];
    }

    public function agregarProducto()
    {
        $this->detallesProductos[] = [
            'idProducto' => '',
            'cantidad' => '',
            'precioUnitario' => '',
        ];
    }

    public function agregarInsumo()
    {
        $this->detallesInsumos[] = [
            'idInsumo' => '',
            'cantidad' => '',
            'precioUnitario' => '',
        ];
    }

    public function cargarPrecioProducto($index)
    {
        try {
            $idProducto = intval($this->detallesProductos[$index]['idProducto']);
            
            if ($idProducto > 0) {
                $producto = Producto::find($idProducto);
                if ($producto) {
                    $this->detallesProductos[$index]['precioUnitario'] = $producto->precioVenta;
                    $this->calcularTotal();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error cargando precio: ' . $e->getMessage());
        }
    }

    public function cargarPrecioInsumo($index)
    {
        try {
            $idInsumo = intval($this->detallesInsumos[$index]['idInsumo']);
            
            if ($idInsumo > 0) {
                $insumo = Insumo::find($idInsumo);
                if ($insumo) {
                    $this->detallesInsumos[$index]['precioUnitario'] = $insumo->precioInsumo ?? 0;
                    $this->calcularTotal();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error cargando precio insumo: ' . $e->getMessage());
        }
    }

    public function eliminarProducto($index)
    {
        unset($this->detallesProductos[$index]);
        $this->detallesProductos = array_values($this->detallesProductos);
        $this->calcularTotal();
    }

    public function eliminarInsumo($index)
    {
        unset($this->detallesInsumos[$index]);
        $this->detallesInsumos = array_values($this->detallesInsumos);
        $this->calcularTotal();
    }

    #[\Livewire\Attributes\On('updated')]
    public function updated($field)
    {
        \Log::info('Updated field: ' . $field);
        if (str_contains($field, 'cantidad') || str_contains($field, 'precioUnitario')) {
            \Log::info('Recalculando totales...');
            $this->calcularTotal();
        }
    }

    protected function calcularTotal()
    {
        try {
            $this->totalProductos = 0;
            $this->totalInsumos = 0;

            foreach ($this->detallesProductos as $item) {
                $this->totalProductos += (floatval($item['cantidad'] ?? 0) * floatval($item['precioUnitario'] ?? 0));
            }

            foreach ($this->detallesInsumos as $item) {
                $this->totalInsumos += (floatval($item['cantidad'] ?? 0) * floatval($item['precioUnitario'] ?? 0));
            }

            $this->subtotal = $this->totalProductos + $this->totalInsumos;
            $this->igv = round($this->subtotal * 0.18, 2);
            $this->totalPagar = $this->subtotal + $this->igv;
        } catch (\Exception $e) {
            \Log::error('Error calculando totales: ' . $e->getMessage());
        }
    }

    public function guardarRecepcion()
    {
        try {
            if (empty($this->idOrden)) {
                session()->flash('error', 'Debe seleccionar una orden de abastecimiento');
                return;
            }

            if (empty($this->detallesProductos) && empty($this->detallesInsumos)) {
                session()->flash('error', 'Debe agregar al menos un producto o insumo');
                return;
            }

            DB::beginTransaction();

            // 1. Actualizar stock de productos
            foreach ($this->detallesProductos as $detalle) {
                if (!empty($detalle['idProducto']) && !empty($detalle['cantidad'])) {
                    $producto = Producto::find($detalle['idProducto']);
                    if ($producto) {
                        $producto->stock = ($producto->stock ?? 0) + (int)$detalle['cantidad'];
                        $producto->save();

                        // Registrar en tabla add_producto
                        $maxId = DB::table('add_producto')
                            ->where('idOrden', (int)$this->idOrden)
                            ->max('idDetalleOpAddProducto') ?? 0;
                        
                        DB::table('add_producto')->insert([
                            'idDetalleOpAddProducto' => $maxId + 1,
                            'idOrden' => (int)$this->idOrden,
                            'idProducto' => (int)$detalle['idProducto'],
                            'cantidad' => (int)$detalle['cantidad'],
                            'precio' => (float)$detalle['precioUnitario'],
                        ]);
                    }
                }
            }

            // 2. Actualizar stock de insumos
            foreach ($this->detallesInsumos as $detalle) {
                if (!empty($detalle['idInsumo']) && !empty($detalle['cantidad'])) {
                    $insumo = Insumo::find($detalle['idInsumo']);
                    if ($insumo) {
                        $insumo->stock = ($insumo->stock ?? 0) + (int)$detalle['cantidad'];
                        $insumo->save();

                        // Registrar en tabla add_insumo
                        $maxId = DB::table('add_insumo')
                            ->where('idOrden', (int)$this->idOrden)
                            ->max('idDetalleOpAddInsumo') ?? 0;
                        
                        DB::table('add_insumo')->insert([
                            'idDetalleOpAddInsumo' => $maxId + 1,
                            'idOrden' => (int)$this->idOrden,
                            'idInsumo' => (int)$detalle['idInsumo'],
                            'cantidad' => (int)$detalle['cantidad'],
                            'precio' => (float)$detalle['precioUnitario'],
                        ]);
                    }
                }
            }

            // 3. Crear factura de pago (pago_orden)
            $nextPagoId = (DB::table('pago_orden')->max('idPagoOrden') ?? 0) + 1;
            
            DB::table('pago_orden')->insert([
                'idPagoOrden' => $nextPagoId,
                'montoTotal' => $this->totalPagar, // Incluye IGV
                'estado' => 1, // 1 = pendiente, 2 = pagado
                'idTipoPago' => 1, // 1 = Efectivo (por defecto)
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 4. Actualizar orden como completada
            DB::table('orden_abastecimiento')
                ->where('idOrden', (int)$this->idOrden)
                ->update([
                    'estado' => 'completada',
                    'fechaEntrega' => now(),
                    'idPagoOrden' => $nextPagoId,
                    'updated_at' => now()
                ]);

            DB::commit();

            session()->flash('success', '✅ Recepción completada. Factura #' . $nextPagoId . ' generada. Pendiente de pago.');
            $this->showForm = false;
            $this->resetForm();
            $this->loadOrdenes();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', '❌ Error al guardar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.almacen.recepcion-mercaderia');
    }
}

