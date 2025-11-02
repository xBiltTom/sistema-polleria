<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Cambiar el estado de un pedido
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estadoPedido' => 'required|in:Pendiente,En Preparación,Listo,Entregado,Cancelado'
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->estadoPedido = $request->estadoPedido;
        $pedido->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado del pedido actualizado correctamente',
            'pedido' => $pedido
        ]);
    }

    /**
     * Calcular el total de un pedido
     */
    public function calcularTotal($id)
    {
        $pedido = Pedido::with(['detalles.plato', 'agregados.producto'])->findOrFail($id);

        $totalPlatos = $pedido->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->plato->precioVenta;
        });

        $totalProductos = $pedido->agregados->sum(function($agregado) {
            return $agregado->cantidad * $agregado->producto->precioVenta;
        });

        $total = $totalPlatos + $totalProductos;

        return response()->json([
            'success' => true,
            'total' => $total,
            'desglose' => [
                'platos' => $totalPlatos,
                'productos' => $totalProductos
            ]
        ]);
    }

    /**
     * Obtener pedidos por estado
     */
    public function pedidosPorEstado($estado)
    {
        $pedidos = Pedido::with(['cliente', 'mesa', 'mozo', 'detalles.plato'])
            ->where('estadoPedido', $estado)
            ->orderBy('fechaPedido', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Obtener pedidos del día
     */
    public function pedidosDelDia()
    {
        $pedidos = Pedido::with(['cliente', 'mesa', 'mozo'])
            ->whereDate('fechaPedido', today())
            ->orderBy('fechaPedido', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'total' => $pedidos->count(),
            'pedidos' => $pedidos
        ]);
    }

    /**
     * Reporte de ventas por rango de fechas
     */
    public function reporteVentas(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $pedidos = Pedido::with(['detalles.plato', 'agregados.producto', 'pagos'])
            ->whereBetween('fechaPedido', [$request->fecha_inicio, $request->fecha_fin])
            ->where('estadoPedido', '!=', 'Cancelado')
            ->get();

        $totalVentas = 0;
        $cantidadPedidos = $pedidos->count();
        $detalleVentas = [];

        foreach ($pedidos as $pedido) {
            $totalPedido = 0;

            foreach ($pedido->detalles as $detalle) {
                $totalPedido += $detalle->cantidad * $detalle->plato->precioVenta;
            }

            foreach ($pedido->agregados as $agregado) {
                $totalPedido += $agregado->cantidad * $agregado->producto->precioVenta;
            }

            $totalVentas += $totalPedido;
            $detalleVentas[] = [
                'idPedido' => $pedido->idPedido,
                'fecha' => $pedido->fechaPedido,
                'cliente' => $pedido->cliente->nombreCliente ?? 'Cliente General',
                'total' => $totalPedido
            ];
        }

        return response()->json([
            'success' => true,
            'resumen' => [
                'total_ventas' => $totalVentas,
                'cantidad_pedidos' => $cantidadPedidos,
                'promedio_por_pedido' => $cantidadPedidos > 0 ? $totalVentas / $cantidadPedidos : 0
            ],
            'detalle' => $detalleVentas
        ]);
    }

    /**
     * Platos más vendidos
     */
    public function platosMasVendidos(Request $request)
    {
        $dias = $request->input('dias', 30);

        $platos = DB::table('detalle_pedido')
            ->join('plato', 'detalle_pedido.idPlato', '=', 'plato.idPlato')
            ->join('pedido', 'detalle_pedido.idPedido', '=', 'pedido.idPedido')
            ->where('pedido.fechaPedido', '>=', now()->subDays($dias))
            ->where('pedido.estadoPedido', '!=', 'Cancelado')
            ->select(
                'plato.idPlato',
                'plato.nombrePlato',
                DB::raw('SUM(detalle_pedido.cantidad) as total_vendido'),
                DB::raw('SUM(detalle_pedido.cantidad * plato.precioVenta) as ingresos')
            )
            ->groupBy('plato.idPlato', 'plato.nombrePlato')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'periodo' => "Últimos {$dias} días",
            'platos' => $platos
        ]);
    }

    /**
     * Cancelar un pedido
     */
    public function cancelarPedido($id, Request $request)
    {
        $request->validate([
            'motivo' => 'nullable|string|max:500'
        ]);

        $pedido = Pedido::findOrFail($id);

        if ($pedido->estadoPedido === 'Entregado') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede cancelar un pedido ya entregado'
            ], 400);
        }

        $pedido->estadoPedido = 'Cancelado';
        if ($request->has('motivo')) {
            $pedido->descripcionPedido = ($pedido->descripcionPedido ?? '') .
                " | CANCELADO: " . $request->motivo;
        }
        $pedido->save();

        return response()->json([
            'success' => true,
            'message' => 'Pedido cancelado correctamente',
            'pedido' => $pedido
        ]);
    }
}
