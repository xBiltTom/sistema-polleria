<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Insumo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Dashboard principal con resumen general
     */
    public function dashboard()
    {
        $hoy = today();

        // Pedidos del día
        $pedidosHoy = Pedido::whereDate('fechaPedido', $hoy)->count();
        $ventasHoy = $this->calcularVentasDia($hoy);

        // Pedidos por estado
        $pedidosPendientes = Pedido::where('estadoPedido', 'Pendiente')->count();
        $pedidosEnPreparacion = Pedido::where('estadoPedido', 'En Preparación')->count();

        // Productos con stock bajo
        $productosStockBajo = Producto::where('stock', '<', 10)->count();
        $insumosStockBajo = Insumo::where('stock', '<', 5)->count();

        // Ingresos del mes
        $mesActual = now()->startOfMonth();
        $ingresosMes = $this->calcularVentasPeriodo($mesActual, now());

        return response()->json([
            'success' => true,
            'resumen' => [
                'pedidos_hoy' => $pedidosHoy,
                'ventas_hoy' => $ventasHoy,
                'pedidos_pendientes' => $pedidosPendientes,
                'pedidos_en_preparacion' => $pedidosEnPreparacion,
                'productos_stock_bajo' => $productosStockBajo,
                'insumos_stock_bajo' => $insumosStockBajo,
                'ingresos_mes' => $ingresosMes
            ]
        ]);
    }

    /**
     * Reporte de ventas por período
     */
    public function ventasPorPeriodo(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_pedido' => 'nullable|in:Delivery,Local,Para Llevar'
        ]);

        $query = Pedido::with(['detalles.plato', 'agregados.producto', 'cliente', 'tipoPedido'])
            ->whereBetween('fechaPedido', [$request->fecha_inicio, $request->fecha_fin])
            ->where('estadoPedido', '!=', 'Cancelado');

        if ($request->has('tipo_pedido')) {
            $query->whereHas('tipoPedido', function($q) use ($request) {
                $q->where('nombreTipoPedido', $request->tipo_pedido);
            });
        }

        $pedidos = $query->get();

        $totalVentas = 0;
        $ventasPorDia = [];
        $ventasPorTipo = [];

        foreach ($pedidos as $pedido) {
            $totalPedido = $this->calcularTotalPedido($pedido);
            $totalVentas += $totalPedido;

            // Agrupar por día
            $dia = $pedido->fechaPedido->format('Y-m-d');
            if (!isset($ventasPorDia[$dia])) {
                $ventasPorDia[$dia] = ['fecha' => $dia, 'total' => 0, 'cantidad' => 0];
            }
            $ventasPorDia[$dia]['total'] += $totalPedido;
            $ventasPorDia[$dia]['cantidad']++;

            // Agrupar por tipo
            $tipo = $pedido->tipoPedido->nombreTipoPedido ?? 'Sin tipo';
            if (!isset($ventasPorTipo[$tipo])) {
                $ventasPorTipo[$tipo] = ['tipo' => $tipo, 'total' => 0, 'cantidad' => 0];
            }
            $ventasPorTipo[$tipo]['total'] += $totalPedido;
            $ventasPorTipo[$tipo]['cantidad']++;
        }

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'resumen' => [
                'total_ventas' => $totalVentas,
                'cantidad_pedidos' => $pedidos->count(),
                'promedio_por_pedido' => $pedidos->count() > 0 ? $totalVentas / $pedidos->count() : 0
            ],
            'ventas_por_dia' => array_values($ventasPorDia),
            'ventas_por_tipo' => array_values($ventasPorTipo)
        ]);
    }

    /**
     * Platos más vendidos por categoría
     */
    public function platosPorCategoria(Request $request)
    {
        $dias = $request->input('dias', 30);

        $platosPorCategoria = DB::table('detalle_pedido')
            ->join('plato', 'detalle_pedido.idPlato', '=', 'plato.idPlato')
            ->join('categoria_plato', 'plato.idCategoria', '=', 'categoria_plato.idCategoriaPlato')
            ->join('pedido', 'detalle_pedido.idPedido', '=', 'pedido.idPedido')
            ->where('pedido.fechaPedido', '>=', now()->subDays($dias))
            ->where('pedido.estadoPedido', '!=', 'Cancelado')
            ->select(
                'categoria_plato.nombreCategoria',
                'plato.nombrePlato',
                DB::raw('SUM(detalle_pedido.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_pedido.cantidad * plato.precioVenta) as ingresos')
            )
            ->groupBy('categoria_plato.nombreCategoria', 'plato.nombrePlato')
            ->orderBy('categoria_plato.nombreCategoria')
            ->orderBy('cantidad_vendida', 'desc')
            ->get();

        // Agrupar por categoría
        $resultado = [];
        foreach ($platosPorCategoria as $plato) {
            if (!isset($resultado[$plato->nombreCategoria])) {
                $resultado[$plato->nombreCategoria] = [
                    'categoria' => $plato->nombreCategoria,
                    'platos' => []
                ];
            }
            $resultado[$plato->nombreCategoria]['platos'][] = [
                'nombre' => $plato->nombrePlato,
                'cantidad_vendida' => $plato->cantidad_vendida,
                'ingresos' => $plato->ingresos
            ];
        }

        return response()->json([
            'success' => true,
            'periodo' => "Últimos {$dias} días",
            'categorias' => array_values($resultado)
        ]);
    }

    /**
     * Reporte de clientes más frecuentes
     */
    public function clientesFrecuentes(Request $request)
    {
        $dias = $request->input('dias', 90);

        $clientes = DB::table('pedido')
            ->join('cliente', 'pedido.idCliente', '=', 'cliente.idCliente')
            ->where('pedido.fechaPedido', '>=', now()->subDays($dias))
            ->where('pedido.estadoPedido', '!=', 'Cancelado')
            ->select(
                'cliente.idCliente',
                'cliente.nombreCliente',
                'cliente.apellidoCliente',
                'cliente.celularCliente',
                DB::raw('COUNT(pedido.idPedido) as cantidad_pedidos')
            )
            ->groupBy('cliente.idCliente', 'cliente.nombreCliente', 'cliente.apellidoCliente', 'cliente.celularCliente')
            ->orderBy('cantidad_pedidos', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'periodo' => "Últimos {$dias} días",
            'clientes' => $clientes
        ]);
    }

    /**
     * Reporte de rendimiento de empleados (mozos)
     */
    public function rendimientoMozos(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $mozos = DB::table('pedido')
            ->join('empleado', 'pedido.idMozo', '=', 'empleado.idEmpleado')
            ->whereBetween('pedido.fechaPedido', [$request->fecha_inicio, $request->fecha_fin])
            ->where('pedido.estadoPedido', '!=', 'Cancelado')
            ->select(
                'empleado.idEmpleado',
                'empleado.nombreEmpleado',
                'empleado.apellidoEmpleado',
                DB::raw('COUNT(pedido.idPedido) as cantidad_pedidos')
            )
            ->groupBy('empleado.idEmpleado', 'empleado.nombreEmpleado', 'empleado.apellidoEmpleado')
            ->orderBy('cantidad_pedidos', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'mozos' => $mozos
        ]);
    }

    /**
     * Análisis de horarios pico
     */
    public function horariosPico(Request $request)
    {
        $dias = $request->input('dias', 30);

        $pedidosPorHora = DB::table('pedido')
            ->where('fechaPedido', '>=', now()->subDays($dias))
            ->where('estadoPedido', '!=', 'Cancelado')
            ->select(
                DB::raw('HOUR(fechaPedido) as hora'),
                DB::raw('COUNT(*) as cantidad_pedidos')
            )
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();

        return response()->json([
            'success' => true,
            'periodo' => "Últimos {$dias} días",
            'horarios' => $pedidosPorHora
        ]);
    }

    /**
     * Reporte de productos agregados más vendidos
     */
    public function productosAgregadosMasVendidos(Request $request)
    {
        $dias = $request->input('dias', 30);

        $productos = DB::table('agregado_pedido')
            ->join('producto', 'agregado_pedido.idProducto', '=', 'producto.idProducto')
            ->join('pedido', 'agregado_pedido.idPedido', '=', 'pedido.idPedido')
            ->where('pedido.fechaPedido', '>=', now()->subDays($dias))
            ->where('pedido.estadoPedido', '!=', 'Cancelado')
            ->select(
                'producto.idProducto',
                'producto.nombre',
                DB::raw('SUM(agregado_pedido.cantidad) as total_vendido'),
                DB::raw('SUM(agregado_pedido.cantidad * producto.precioVenta) as ingresos')
            )
            ->groupBy('producto.idProducto', 'producto.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'periodo' => "Últimos {$dias} días",
            'productos' => $productos
        ]);
    }

    // Métodos auxiliares privados

    private function calcularVentasDia($fecha)
    {
        $pedidos = Pedido::with(['detalles.plato', 'agregados.producto'])
            ->whereDate('fechaPedido', $fecha)
            ->where('estadoPedido', '!=', 'Cancelado')
            ->get();

        return $pedidos->sum(function($pedido) {
            return $this->calcularTotalPedido($pedido);
        });
    }

    private function calcularVentasPeriodo($inicio, $fin)
    {
        $pedidos = Pedido::with(['detalles.plato', 'agregados.producto'])
            ->whereBetween('fechaPedido', [$inicio, $fin])
            ->where('estadoPedido', '!=', 'Cancelado')
            ->get();

        return $pedidos->sum(function($pedido) {
            return $this->calcularTotalPedido($pedido);
        });
    }

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
