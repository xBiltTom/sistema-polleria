<?php

namespace App\Http\Controllers;

use App\Models\PagosPedidos;
use App\Models\PagoOrden;
use App\Models\Pedido;
use App\Models\OrdenAbastecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /**
     * Registrar un pago para un pedido
     */
    public function registrarPagoPedido(Request $request)
    {
        $request->validate([
            'idPedido' => 'required|exists:pedido,idPedido',
            'idMetodoPago' => 'required|exists:metodo_pago_pedido,idMetodo',
            'monto' => 'required|numeric|min:0',
            'nroOperacion' => 'nullable|string|max:100'
        ]);

        $pedido = Pedido::with(['detalles.plato', 'agregados.producto'])->findOrFail($request->idPedido);

        // Calcular el total del pedido
        $totalPedido = $this->calcularTotalPedido($pedido);

        // Verificar pagos existentes
        $totalPagado = PagosPedidos::where('idPedido', $request->idPedido)->sum('monto');

        if (($totalPagado + $request->monto) > $totalPedido) {
            return response()->json([
                'success' => false,
                'message' => 'El monto total pagado excede el total del pedido',
                'total_pedido' => $totalPedido,
                'total_pagado' => $totalPagado,
                'saldo_pendiente' => $totalPedido - $totalPagado
            ], 400);
        }

        $pago = PagosPedidos::create([
            'idPedido' => $request->idPedido,
            'idMetodoPago' => $request->idMetodoPago,
            'monto' => $request->monto,
            'nroOperacion' => $request->nroOperacion
        ]);

        // Verificar si el pedido está completamente pagado
        $nuevoTotalPagado = $totalPagado + $request->monto;
        $completamentePagado = abs($nuevoTotalPagado - $totalPedido) < 0.01;

        return response()->json([
            'success' => true,
            'message' => 'Pago registrado correctamente',
            'pago' => $pago,
            'resumen' => [
                'total_pedido' => $totalPedido,
                'total_pagado' => $nuevoTotalPagado,
                'saldo_pendiente' => $totalPedido - $nuevoTotalPagado,
                'completamente_pagado' => $completamentePagado
            ]
        ]);
    }

    /**
     * Obtener estado de pago de un pedido
     */
    public function estadoPagoPedido($idPedido)
    {
        $pedido = Pedido::with(['detalles.plato', 'agregados.producto'])->findOrFail($idPedido);
        $totalPedido = $this->calcularTotalPedido($pedido);

        $pagos = PagosPedidos::with('metodoPago')
            ->where('idPedido', $idPedido)
            ->get();

        $totalPagado = $pagos->sum('monto');
        $saldoPendiente = $totalPedido - $totalPagado;
        $completamentePagado = abs($saldoPendiente) < 0.01;

        return response()->json([
            'success' => true,
            'idPedido' => $idPedido,
            'total_pedido' => $totalPedido,
            'total_pagado' => $totalPagado,
            'saldo_pendiente' => $saldoPendiente,
            'completamente_pagado' => $completamentePagado,
            'pagos' => $pagos
        ]);
    }

    /**
     * Registrar un pago para una orden de abastecimiento
     */
    public function registrarPagoOrden(Request $request)
    {
        $request->validate([
            'idOrden' => 'required|exists:orden_abastecimiento,idOrden',
            'idTipoPago' => 'required|exists:tipo_pago_orden,idTipoPago',
            'nroOperacion' => 'nullable|string|max:100',
            'fechaPago' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            // Crear el registro de pago
            $pagoOrden = PagoOrden::create([
                'idOrden' => $request->idOrden,
                'idTipoPago' => $request->idTipoPago,
                'fechaPago' => $request->fechaPago
            ]);

            // Actualizar la orden con el ID de pago
            $orden = OrdenAbastecimiento::findOrFail($request->idOrden);
            $orden->idPagoOrden = $pagoOrden->idPagoOrden;
            $orden->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago de orden registrado correctamente',
                'pago' => $pagoOrden
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener resumen de pagos por método
     */
    public function resumenPagosPorMetodo(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $pagosPorMetodo = DB::table('pagos_pedidos')
            ->join('pedido', 'pagos_pedidos.idPedido', '=', 'pedido.idPedido')
            ->join('metodo_pago_pedido', 'pagos_pedidos.idMetodoPago', '=', 'metodo_pago_pedido.idMetodo')
            ->whereBetween('pedido.fechaPedido', [$request->fecha_inicio, $request->fecha_fin])
            ->select(
                'metodo_pago_pedido.nombreMetodo',
                DB::raw('COUNT(DISTINCT pagos_pedidos.idPago) as cantidad_transacciones'),
                DB::raw('SUM(pagos_pedidos.monto) as total_recaudado')
            )
            ->groupBy('metodo_pago_pedido.nombreMetodo')
            ->orderBy('total_recaudado', 'desc')
            ->get();

        $totalGeneral = $pagosPorMetodo->sum('total_recaudado');

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'total_general' => $totalGeneral,
            'pagos_por_metodo' => $pagosPorMetodo
        ]);
    }

    /**
     * Listar pedidos pendientes de pago
     */
    public function pedidosPendientesPago()
    {
        $pedidos = Pedido::with(['cliente', 'detalles.plato', 'agregados.producto'])
            ->whereIn('estadoPedido', ['Pendiente', 'En Preparación', 'Listo', 'Entregado'])
            ->get();

        $pedidosPendientes = [];

        foreach ($pedidos as $pedido) {
            $totalPedido = $this->calcularTotalPedido($pedido);
            $totalPagado = PagosPedidos::where('idPedido', $pedido->idPedido)->sum('monto');
            $saldoPendiente = $totalPedido - $totalPagado;

            if ($saldoPendiente > 0.01) {
                $pedidosPendientes[] = [
                    'idPedido' => $pedido->idPedido,
                    'fechaPedido' => $pedido->fechaPedido,
                    'cliente' => $pedido->cliente->nombreCliente ?? 'Cliente General',
                    'total_pedido' => $totalPedido,
                    'total_pagado' => $totalPagado,
                    'saldo_pendiente' => $saldoPendiente,
                    'estado' => $pedido->estadoPedido
                ];
            }
        }

        return response()->json([
            'success' => true,
            'cantidad' => count($pedidosPendientes),
            'pedidos' => $pedidosPendientes
        ]);
    }

    /**
     * Reporte de ingresos diarios
     */
    public function ingresosDiarios(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $ingresosPorDia = DB::table('pagos_pedidos')
            ->join('pedido', 'pagos_pedidos.idPedido', '=', 'pedido.idPedido')
            ->whereBetween('pedido.fechaPedido', [$request->fecha_inicio, $request->fecha_fin])
            ->select(
                DB::raw('DATE(pedido.fechaPedido) as fecha'),
                DB::raw('COUNT(DISTINCT pagos_pedidos.idPedido) as cantidad_pagos'),
                DB::raw('SUM(pagos_pedidos.monto) as total_ingreso')
            )
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $totalPeriodo = $ingresosPorDia->sum('total_ingreso');

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'total_periodo' => $totalPeriodo,
            'ingresos_diarios' => $ingresosPorDia
        ]);
    }

    /**
     * Anular un pago
     */
    public function anularPago(Request $request, $idPago)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        $pago = PagosPedidos::findOrFail($idPago);

        // Verificar que el pedido no esté entregado
        $pedido = Pedido::findOrFail($pago->idPedido);
        if ($pedido->estadoPedido === 'Entregado') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede anular un pago de un pedido ya entregado'
            ], 400);
        }

        $pago->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pago anulado correctamente',
            'motivo' => $request->motivo
        ]);
    }

    // Método auxiliar privado

    private function calcularTotalPedido($pedido)
    {
        $totalPlatos = $pedido->detalles->sum(function($detalle) {
            return $detalle->cantidad * $detalle->plato->precioVenta;
        });

        $totalProductos = $pedido->agregados->sum(function($agregado) {
            return $agregado->cantidad * $agregado->producto->precioVenta;
        });

        return $totalPlatos + $totalProductos;
    }
}
