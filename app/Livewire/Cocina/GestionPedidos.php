<?php

namespace App\Livewire\Cocina;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\PreparacionPlato;
use Illuminate\Support\Facades\DB;

class GestionPedidos extends Component
{
    use WithPagination;

    public $filtroEstado = 'pendiente';
    public $pedidoExpandido = null;

    public function render()
    {
        $pedidos = Pedido::where('estadoPedido', $this->filtroEstado)
            ->with(['cliente', 'mesa', 'mozo', 'detalles.plato', 'detalles.producto'])
            ->orderBy('fechaPedido', 'desc')
            ->paginate(10);

        return view('livewire.cocina.gestion-pedidos', [
            'pedidos' => $pedidos,
        ]);
    }

    public function cambiarEstadoPedido($idPedido, $nuevoEstado)
    {
        $pedido = Pedido::find($idPedido);
        
        if ($pedido) {
            $pedido->update(['estadoPedido' => $nuevoEstado]);
            
            // Si se marca como completado, actualizar todos los detalles
            if ($nuevoEstado === 'completado') {
                DetallePedido::where('idPedido', $idPedido)->update(['estado' => 'completado']);
            }
        }
    }

    public function cambiarEstadoDetalle($idDetalle, $nuevoEstado)
    {
        try {
            $detalle = DetallePedido::find($idDetalle);
            
            if ($detalle) {
                $detalle->update(['estado' => $nuevoEstado]);
                
                // Registrar el inicio de preparación si cambia a "preparando"
                if ($nuevoEstado === 'preparando') {
                    // Obtener el idCocinero del usuario autenticado
                    $idCocinero = auth()->user()->idEmpleado ?? 1; // Por defecto 1 si no existe
                    
                    PreparacionPlato::firstOrCreate(
                        ['idDetalle' => $idDetalle, 'idPedido' => $detalle->idPedido],
                        ['fechaInicio' => now(), 'idCocinero' => $idCocinero, 'estado' => 'preparando']
                    );
                } elseif ($nuevoEstado === 'completado') {
                    // Completar la preparación
                    PreparacionPlato::where('idDetalle', $idDetalle)
                        ->where('idPedido', $detalle->idPedido)
                        ->update(['fechaFin' => now(), 'estado' => 'completado']);
                }
            }
        } catch (\Exception $e) {
            // Log del error
            \Log::error('Error al cambiar estado del detalle', [
                'idDetalle' => $idDetalle,
                'nuevoEstado' => $nuevoEstado,
                'error' => $e->getMessage()
            ]);
            
            $this->dispatch('notify', type: 'error', message: 'Error al actualizar estado: ' . $e->getMessage());
        }
    }

    public function expandirPedido($idPedido)
    {
        $this->pedidoExpandido = $this->pedidoExpandido === $idPedido ? null : $idPedido;
    }

    public function getEstadoClass($estado)
    {
        return match ($estado) {
            'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'preparando' => 'bg-blue-100 text-blue-800 border-blue-300',
            'completado' => 'bg-green-100 text-green-800 border-green-300',
            'entregado' => 'bg-purple-100 text-purple-800 border-purple-300',
            'cancelado' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
