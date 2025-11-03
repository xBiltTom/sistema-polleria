<?php

namespace App\Livewire\Mozo;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\Mesa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CobrarPedido extends Component
{
    public $pedidosEntregados = [];
    public $pedidoSeleccionado = null;
    public $montoRecibido = 0;
    public $metodoPago = 'efectivo'; // efectivo, tarjeta, transferencia

    public function mount()
    {
        $this->cargarPedidosEntregados();
    }

    public function render()
    {
        // Recargar lista cada vez que se renderiza
        $this->cargarPedidosEntregados();
        
        return view('livewire.mozo.cobrar-pedido', [
            'pedidosEntregados' => $this->pedidosEntregados,
            'pedidoActual' => $this->pedidoSeleccionado ? Pedido::find($this->pedidoSeleccionado) : null,
        ]);
    }

    public function cargarPedidosEntregados()
    {
        $this->pedidosEntregados = Pedido::where('estadoPedido', 'entregado')
            ->where('estadoPago', 'pendiente')
            ->with(['cliente', 'mesa', 'detalles'])
            ->orderBy('fechaPedido', 'asc')
            ->get()
            ->map(function ($pedido) {
                return [
                    'idPedido' => $pedido->idPedido,
                    'nroMesa' => $pedido->mesa->nroMesa ?? 'N/A',
                    'cliente' => $pedido->cliente->nombreCliente ?? 'Sin nombre',
                    'total' => $pedido->detalles->sum(function ($detalle) {
                        return $detalle->cantidad * $detalle->precioUnitario;
                    }),
                    'horaEntrega' => $pedido->fechaPedido->format('H:i'),
                ];
            })
            ->toArray();
    }

    public function seleccionarPedido($idPedido)
    {
        $this->pedidoSeleccionado = $idPedido;
        $this->montoRecibido = 0;
        $this->metodoPago = 'efectivo';
    }

    public function cerrarPedido()
    {
        $this->pedidoSeleccionado = null;
        $this->montoRecibido = 0;
    }

    public function realizarCobro()
    {
        if (!$this->pedidoSeleccionado) {
            session()->flash('error', 'Selecciona un pedido');
            return;
        }

        $pedido = Pedido::find($this->pedidoSeleccionado);
        if (!$pedido) {
            session()->flash('error', 'Pedido no encontrado');
            return;
        }

        // Calcular total
        $total = $pedido->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precioUnitario;
        });

        $montoRecibidoFloat = (float) $this->montoRecibido;
        if ($montoRecibidoFloat < $total && $this->metodoPago === 'efectivo') {
            session()->flash('error', 'Monto insuficiente');
            return;
        }

        try {
            DB::beginTransaction();

            // Registrar pago
            $pedido->update([
                'estadoPago' => 'pagado',
                'totalPedido' => $total,
                'fechaPago' => Carbon::now(),
            ]);

            // Liberar mesa
            Mesa::where('idMesa', $pedido->idMesa)->update(['estado' => 'disponible']);

            DB::commit();

            session()->flash('success', sprintf(
                'Cobro realizado: S/ %.2f | Cambio: S/ %.2f | Mesa %s liberada',
                $total,
                max(0, $montoRecibidoFloat - $total),
                $pedido->mesa->nroMesa ?? 'N/A'
            ));

            // Limpiar y recargar
            $this->pedidoSeleccionado = null;
            $this->montoRecibido = 0;
            $this->metodoPago = 'efectivo';
            $this->cargarPedidosEntregados();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al realizar cobro: ' . $e->getMessage());
        }
    }

    public function calcularCambio()
    {
        if (!$this->pedidoSeleccionado) return 0;

        $pedido = Pedido::find($this->pedidoSeleccionado);
        $total = $pedido->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precioUnitario;
        });

        $montoRecibidoFloat = (float) $this->montoRecibido;
        return max(0, $montoRecibidoFloat - $total);
    }
}
