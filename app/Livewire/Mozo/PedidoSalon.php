<?php

namespace App\Livewire\Mozo;

use Livewire\Component;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Plato;
use App\Models\Producto;
use App\Models\DetallePedido;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Livewire\Attributes\Computed;

class PedidoSalon extends Component
{
    public $step = 'seleccionar-mesa'; // seleccionar-mesa, registrar-cliente, tomar-pedido
    public $mesas = [];
    public $mesaSeleccionada = null;
    
    // Datos del cliente
    public $nombreCliente = '';
    public $celularCliente = '';
    public $dniCliente = '';
    
    // Datos del pedido
    public $pedidoItems = [];
    public $totalPedido = 0;
    public $busquedaPlatos = '';
    public $busquedaProductos = '';
    
    #[Computed]
    public function platosBuscados()
    {
        if (strlen($this->busquedaPlatos) < 2) {
            return [];
        }

        return Plato::where('nombrePlato', 'LIKE', '%' . $this->busquedaPlatos . '%')
            ->orWhere('descripcion', 'LIKE', '%' . $this->busquedaPlatos . '%')
            ->where('stock', '>', 0)
            ->limit(5)
            ->get()
            ->map(function ($plato) {
                return [
                    'idPlato' => $plato->idPlato,
                    'nombrePlato' => $plato->nombrePlato,
                    'precioVenta' => $plato->precioVenta,
                    'stock' => $plato->stock,
                    'tipo' => 'plato',
                ];
            })
            ->toArray();
    }

    #[Computed]
    public function productosBuscados()
    {
        if (strlen($this->busquedaProductos) < 2) {
            return [];
        }

        return Producto::where('nombre', 'LIKE', '%' . $this->busquedaProductos . '%')
            ->orWhere('descripcion', 'LIKE', '%' . $this->busquedaProductos . '%')
            ->where('stock', '>', 0)
            ->limit(5)
            ->get()
            ->map(function ($producto) {
                return [
                    'idProducto' => $producto->idProducto,
                    'nombre' => $producto->nombre,
                    'precioVenta' => $producto->precioVenta,
                    'stock' => $producto->stock,
                    'tipo' => 'producto',
                ];
            })
            ->toArray();
    }
    
    public function mount()
    {
        $this->cargarMesas();
    }

    public function render()
    {
        return view('livewire.mozo.pedido-salon');
    }

    public function cargarMesas()
    {
        $this->mesas = Mesa::all()->map(function ($mesa) {
            return [
                'idMesa' => $mesa->idMesa,
                'nroMesa' => $mesa->nroMesa,
                'capacidadMesa' => $mesa->capacidadMesa,
                'estado' => $mesa->estado ?? 'disponible',
                'estadoClass' => $this->getEstadoClass($mesa->estado ?? 'disponible'),
            ];
        })->toArray();
    }

    public function getEstadoClass($estado)
    {
        return match ($estado) {
            'ocupada' => 'bg-red-100 border-red-300 text-red-800',
            'reservada' => 'bg-yellow-100 border-yellow-300 text-yellow-800',
            'disponible' => 'bg-green-100 border-green-300 text-green-800',
            default => 'bg-gray-100 border-gray-300 text-gray-800',
        };
    }

    public function seleccionarMesa($idMesa)
    {
        $mesa = Mesa::find($idMesa);
        
        if ($mesa && ($mesa->estado === 'disponible' || $mesa->estado === null)) {
            $this->mesaSeleccionada = $idMesa;
            $this->step = 'registrar-cliente';
        }
    }

    public function registrarClienteYProceder()
    {
        $this->validate([
            'nombreCliente' => 'required|string|max:100',
            'celularCliente' => 'nullable|string|max:20',
            'dniCliente' => 'nullable|string|max:20',
        ]);

        // Crear o buscar cliente
        $clienteData = [
            'nombreCliente' => $this->nombreCliente,
            'celularCliente' => $this->celularCliente,
        ];

        // Si hay DNI, buscar por DNI. Si no, crear siempre uno nuevo con el nombre
        if ($this->dniCliente) {
            $cliente = Cliente::firstOrCreate(
                ['dniCliente' => $this->dniCliente],
                $clienteData
            );
        } else {
            // Crear nuevo cliente sin buscar por DNI
            $cliente = Cliente::create($clienteData);
        }

        session()->put('pedido_cliente_id', $cliente->idCliente);
        session()->put('pedido_cliente_nombre', $cliente->nombreCliente);
        
        $this->step = 'tomar-pedido';
    }

    public function agregarPlato($idPlato)
    {
        $plato = Plato::find($idPlato);
        if (!$plato) return;

        $key = "plato_$idPlato";
        
        if (isset($this->pedidoItems[$key])) {
            $this->pedidoItems[$key]['cantidad']++;
        } else {
            $this->pedidoItems[$key] = [
                'tipo' => 'plato',
                'idPlato' => $idPlato,
                'nombre' => $plato->nombrePlato,
                'precio' => $plato->precioVenta,
                'cantidad' => 1,
                'subtotal' => $plato->precioVenta,
                'observacion' => '',
            ];
        }
        
        $this->recalcularTotal();
        $this->busquedaPlatos = '';
    }

    public function agregarProducto($idProducto)
    {
        $producto = Producto::find($idProducto);
        if (!$producto) return;

        $key = "producto_$idProducto";
        
        if (isset($this->pedidoItems[$key])) {
            $this->pedidoItems[$key]['cantidad']++;
        } else {
            $this->pedidoItems[$key] = [
                'tipo' => 'producto',
                'idProducto' => $idProducto,
                'nombre' => $producto->nombre,
                'precio' => $producto->precioVenta,
                'cantidad' => 1,
                'subtotal' => $producto->precioVenta,
                'observacion' => '',
            ];
        }
        
        $this->recalcularTotal();
        $this->busquedaProductos = '';
    }

    public function actualizarCantidad($key, $cantidad)
    {
        if ($cantidad <= 0) {
            $this->eliminarItem($key);
            return;
        }

        if (isset($this->pedidoItems[$key])) {
            $this->pedidoItems[$key]['cantidad'] = $cantidad;
            $this->pedidoItems[$key]['subtotal'] = $cantidad * $this->pedidoItems[$key]['precio'];
        }
        
        $this->recalcularTotal();
    }

    public function actualizarObservacion($key, $observacion)
    {
        if (isset($this->pedidoItems[$key])) {
            $this->pedidoItems[$key]['observacion'] = $observacion;
        }
    }

    public function eliminarItem($key)
    {
        unset($this->pedidoItems[$key]);
        $this->recalcularTotal();
    }

    public function recalcularTotal()
    {
        $this->totalPedido = array_reduce($this->pedidoItems, function ($carry, $item) {
            return $carry + $item['subtotal'];
        }, 0);
    }

    public function registrarPedido()
    {
        if (empty($this->pedidoItems)) {
            session()->flash('error', 'El pedido debe contener al menos un item');
            return;
        }

        try {
            DB::beginTransaction();

            // Obtener datos de sesión
            $clienteId = session()->get('pedido_cliente_id');
            $mozo = auth()->user()->empleado;

            // Crear pedido
            $pedido = Pedido::create([
                'fechaPedido' => Carbon::now(),
                'estadoPedido' => 'pendiente',
                'estadoPago' => 'pendiente',
                'totalPedido' => $this->totalPedido,
                'idTipoPedido' => 1, // Tipo de pedido salón
                'idCliente' => $clienteId,
                'idMesa' => $this->mesaSeleccionada,
                'idMozo' => $mozo->idEmpleado,
            ]);

            // Crear detalles del pedido
            foreach ($this->pedidoItems as $key => $item) {
                $detalleData = [
                    'idPedido' => $pedido->idPedido,
                    'cantidad' => $item['cantidad'],
                    'precioUnitario' => $item['precio'],
                    'estado' => 'pendiente',
                    'observacion' => $item['observacion'],
                ];

                if ($item['tipo'] === 'plato') {
                    $detalleData['idPlato'] = $item['idPlato'];
                } else {
                    $detalleData['idProducto'] = $item['idProducto'];
                }

                DetallePedido::create($detalleData);
            }

            // Marcar mesa como ocupada
            Mesa::where('idMesa', $this->mesaSeleccionada)->update(['estado' => 'ocupada']);

            DB::commit();

            session()->flash('success', 'Pedido registrado exitosamente. Enviando a cocina...');
            
            $this->resetPedido();
            $this->cargarMesas();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al registrar el pedido: ' . $e->getMessage());
        }
    }

    public function resetPedido()
    {
        $this->step = 'seleccionar-mesa';
        $this->pedidoItems = [];
        $this->mesaSeleccionada = null;
        $this->nombreCliente = '';
        $this->celularCliente = '';
        $this->dniCliente = '';
        $this->totalPedido = 0;
        session()->forget(['pedido_cliente_id', 'pedido_cliente_nombre']);
    }

    public function volverAMesas()
    {
        $this->step = 'seleccionar-mesa';
        $this->nombreCliente = '';
        $this->celularCliente = '';
        $this->dniCliente = '';
    }

    public function volverACliente()
    {
        $this->step = 'registrar-cliente';
    }
}
