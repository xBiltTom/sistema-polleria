<?php

namespace App\Http\Controllers;

use App\Models\OrdenAbastecimiento;
use App\Models\AddInsumo;
use App\Models\AddProducto;
use App\Models\Insumo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenAbastecimientoController extends Controller
{
    /**
     * Cambiar el estado de una orden
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Aprobada,En Tránsito,Recibida,Cancelada'
        ]);

        $orden = OrdenAbastecimiento::findOrFail($id);
        $orden->estado = $request->estado;

        // Si la orden se marca como recibida, actualizar el stock
        if ($request->estado === 'Recibida' && $orden->estado !== 'Recibida') {
            $this->actualizarStock($orden);
        }

        $orden->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de la orden actualizado correctamente',
            'orden' => $orden
        ]);
    }

    /**
     * Actualizar el stock cuando se recibe una orden
     */
    private function actualizarStock(OrdenAbastecimiento $orden)
    {
        DB::transaction(function () use ($orden) {
            // Actualizar insumos
            foreach ($orden->addInsumos as $addInsumo) {
                $insumo = Insumo::find($addInsumo->idInsumo);
                if ($insumo) {
                    $insumo->stock += $addInsumo->cantidad;
                    $insumo->save();
                }
            }

            // Actualizar productos
            foreach ($orden->addProductos as $addProducto) {
                $producto = Producto::find($addProducto->idProducto);
                if ($producto) {
                    $producto->stock += $addProducto->cantidad;
                    $producto->save();
                }
            }
        });
    }

    /**
     * Calcular el total de una orden
     */
    public function calcularTotal($id)
    {
        $orden = OrdenAbastecimiento::with(['addInsumos.insumo', 'addProductos.producto'])
            ->findOrFail($id);

        $totalInsumos = $orden->addInsumos->sum(function($add) {
            return $add->cantidad * $add->insumo->precioInsumo;
        });

        $totalProductos = $orden->addProductos->sum(function($add) {
            return $add->cantidad * $add->producto->precioVenta;
        });

        $total = $totalInsumos + $totalProductos;

        return response()->json([
            'success' => true,
            'total' => $total,
            'desglose' => [
                'insumos' => $totalInsumos,
                'productos' => $totalProductos
            ]
        ]);
    }

    /**
     * Aprobar una orden de abastecimiento
     */
    public function aprobarOrden($id, Request $request)
    {
        $orden = OrdenAbastecimiento::findOrFail($id);

        if ($orden->estado !== 'Pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden aprobar órdenes pendientes'
            ], 400);
        }

        $orden->estado = 'Aprobada';
        $orden->save();

        return response()->json([
            'success' => true,
            'message' => 'Orden aprobada correctamente',
            'orden' => $orden
        ]);
    }

    /**
     * Obtener órdenes por estado
     */
    public function ordenesPorEstado($estado)
    {
        $ordenes = OrdenAbastecimiento::with(['proveedor', 'empleado'])
            ->where('estado', $estado)
            ->orderBy('fechaOrden', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'ordenes' => $ordenes
        ]);
    }

    /**
     * Reporte de compras por proveedor
     */
    public function reporteComprasPorProveedor(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'idProveedor' => 'nullable|exists:proveedor,idProveedor'
        ]);

        $query = OrdenAbastecimiento::with(['proveedor', 'addInsumos.insumo', 'addProductos.producto'])
            ->whereBetween('fechaOrden', [$request->fecha_inicio, $request->fecha_fin])
            ->where('estado', '!=', 'Cancelada');

        if ($request->has('idProveedor')) {
            $query->where('idProveedor', $request->idProveedor);
        }

        $ordenes = $query->get();

        $reportePorProveedor = [];
        foreach ($ordenes as $orden) {
            $proveedor = $orden->proveedor->nombreProveedor;

            if (!isset($reportePorProveedor[$proveedor])) {
                $reportePorProveedor[$proveedor] = [
                    'proveedor' => $proveedor,
                    'cantidad_ordenes' => 0,
                    'total_compras' => 0
                ];
            }

            $totalOrden = 0;
            foreach ($orden->addInsumos as $add) {
                $totalOrden += $add->cantidad * $add->insumo->precioInsumo;
            }
            foreach ($orden->addProductos as $add) {
                $totalOrden += $add->cantidad * $add->producto->precioVenta;
            }

            $reportePorProveedor[$proveedor]['cantidad_ordenes']++;
            $reportePorProveedor[$proveedor]['total_compras'] += $totalOrden;
        }

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'reporte' => array_values($reportePorProveedor)
        ]);
    }

    /**
     * Órdenes pendientes de recepción
     */
    public function ordenesPendientesRecepcion()
    {
        $ordenes = OrdenAbastecimiento::with(['proveedor', 'empleado'])
            ->whereIn('estado', ['Aprobada', 'En Tránsito'])
            ->orderBy('fechaEntrega', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'total' => $ordenes->count(),
            'ordenes' => $ordenes
        ]);
    }

    /**
     * Historial de órdenes de un proveedor
     */
    public function historialProveedor($idProveedor)
    {
        $ordenes = OrdenAbastecimiento::with(['addInsumos.insumo', 'addProductos.producto'])
            ->where('idProveedor', $idProveedor)
            ->orderBy('fechaOrden', 'desc')
            ->limit(20)
            ->get();

        $totalCompras = 0;
        foreach ($ordenes as $orden) {
            $total = 0;
            foreach ($orden->addInsumos as $add) {
                $total += $add->cantidad * $add->insumo->precioInsumo;
            }
            foreach ($orden->addProductos as $add) {
                $total += $add->cantidad * $add->producto->precioVenta;
            }
            $orden->total = $total;
            $totalCompras += $total;
        }

        return response()->json([
            'success' => true,
            'total_compras' => $totalCompras,
            'cantidad_ordenes' => $ordenes->count(),
            'ordenes' => $ordenes
        ]);
    }
}
