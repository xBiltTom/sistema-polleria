<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Producto;
use App\Models\AddInsumo;
use App\Models\AddProducto;
use App\Models\DetallePreparacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    /**
     * Obtener resumen general del inventario
     */
    public function resumenInventario()
    {
        // Insumos
        $totalInsumos = Insumo::count();
        $insumosStockBajo = Insumo::where('stock', '<', 5)->count();
        $insumosVencidos = Insumo::where('fechaVencimiento', '<', now())->count();
        $insumosProximosVencer = Insumo::whereBetween('fechaVencimiento', [now(), now()->addDays(7)])->count();

        // Productos
        $totalProductos = Producto::count();
        $productosStockBajo = Producto::where('stock', '<', 10)->count();
        $productosVencidos = Producto::where('fechaVencimiento', '<', now())->count();
        $productosProximosVencer = Producto::whereBetween('fechaVencimiento', [now(), now()->addDays(7)])->count();

        return response()->json([
            'success' => true,
            'insumos' => [
                'total' => $totalInsumos,
                'stock_bajo' => $insumosStockBajo,
                'vencidos' => $insumosVencidos,
                'proximos_vencer' => $insumosProximosVencer
            ],
            'productos' => [
                'total' => $totalProductos,
                'stock_bajo' => $productosStockBajo,
                'vencidos' => $productosVencidos,
                'proximos_vencer' => $productosProximosVencer
            ]
        ]);
    }

    /**
     * Listar insumos con stock bajo
     */
    public function insumosStockBajo(Request $request)
    {
        $limite = $request->input('limite', 5);

        $insumos = Insumo::with(['categoria', 'unidad'])
            ->where('stock', '<', $limite)
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'limite_stock' => $limite,
            'cantidad' => $insumos->count(),
            'insumos' => $insumos
        ]);
    }

    /**
     * Listar productos con stock bajo
     */
    public function productosStockBajo(Request $request)
    {
        $limite = $request->input('limite', 10);

        $productos = Producto::with('categoria')
            ->where('stock', '<', $limite)
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'limite_stock' => $limite,
            'cantidad' => $productos->count(),
            'productos' => $productos
        ]);
    }

    /**
     * Insumos próximos a vencer
     */
    public function insumosProximosVencer(Request $request)
    {
        $dias = $request->input('dias', 7);

        $insumos = Insumo::with(['categoria', 'unidad'])
            ->whereBetween('fechaVencimiento', [now(), now()->addDays($dias)])
            ->orderBy('fechaVencimiento', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'dias' => $dias,
            'cantidad' => $insumos->count(),
            'insumos' => $insumos
        ]);
    }

    /**
     * Productos próximos a vencer
     */
    public function productosProximosVencer(Request $request)
    {
        $dias = $request->input('dias', 7);

        $productos = Producto::with('categoria')
            ->whereBetween('fechaVencimiento', [now(), now()->addDays($dias)])
            ->orderBy('fechaVencimiento', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'dias' => $dias,
            'cantidad' => $productos->count(),
            'productos' => $productos
        ]);
    }

    /**
     * Movimientos de insumos (entradas)
     */
    public function movimientosInsumos(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'idInsumo' => 'nullable|exists:insumo,idInsumo'
        ]);

        $query = AddInsumo::with(['insumo', 'orden.proveedor'])
            ->whereHas('orden', function($q) use ($request) {
                $q->whereBetween('fechaOrden', [$request->fecha_inicio, $request->fecha_fin]);
            });

        if ($request->has('idInsumo')) {
            $query->where('idInsumo', $request->idInsumo);
        }

        $movimientos = $query->orderBy('idOrden', 'desc')->get();

        $resumen = $movimientos->groupBy('idInsumo')->map(function($items) {
            $insumo = $items->first()->insumo;
            return [
                'idInsumo' => $insumo->idInsumo,
                'nombreInsumo' => $insumo->nombreInsumo,
                'total_entradas' => $items->sum('cantidad'),
                'cantidad_movimientos' => $items->count()
            ];
        })->values();

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'resumen' => $resumen,
            'movimientos' => $movimientos
        ]);
    }

    /**
     * Movimientos de productos (entradas)
     */
    public function movimientosProductos(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'idProducto' => 'nullable|exists:producto,idProducto'
        ]);

        $query = AddProducto::with(['producto', 'orden.proveedor'])
            ->whereHas('orden', function($q) use ($request) {
                $q->whereBetween('fechaOrden', [$request->fecha_inicio, $request->fecha_fin]);
            });

        if ($request->has('idProducto')) {
            $query->where('idProducto', $request->idProducto);
        }

        $movimientos = $query->orderBy('idOrden', 'desc')->get();

        $resumen = $movimientos->groupBy('idProducto')->map(function($items) {
            $producto = $items->first()->producto;
            return [
                'idProducto' => $producto->idProducto,
                'nombreProducto' => $producto->nombre,
                'total_entradas' => $items->sum('cantidad'),
                'cantidad_movimientos' => $items->count()
            ];
        })->values();

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'resumen' => $resumen,
            'movimientos' => $movimientos
        ]);
    }

    /**
     * Consumo de insumos por preparación de platos
     */
    public function consumoInsumos(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $consumos = DB::table('detalle_preparacion')
            ->join('insumo', 'detalle_preparacion.idInsumo', '=', 'insumo.idInsumo')
            ->join('preparacion_plato', 'detalle_preparacion.idPreparacion', '=', 'preparacion_plato.idPreparacion')
            ->whereBetween('preparacion_plato.fechaPreparacion', [$request->fecha_inicio, $request->fecha_fin])
            ->select(
                'insumo.idInsumo',
                'insumo.nombreInsumo',
                DB::raw('SUM(detalle_preparacion.cantidad) as total_consumido')
            )
            ->groupBy('insumo.idInsumo', 'insumo.nombreInsumo')
            ->orderBy('total_consumido', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'periodo' => [
                'inicio' => $request->fecha_inicio,
                'fin' => $request->fecha_fin
            ],
            'consumos' => $consumos
        ]);
    }

    /**
     * Valorización del inventario actual
     */
    public function valorizacionInventario()
    {
        // Valorizar insumos
        $insumos = Insumo::all();
        $valorInsumos = $insumos->sum(function($insumo) {
            return $insumo->stock * $insumo->precioInsumo;
        });

        // Valorizar productos
        $productos = Producto::all();
        $valorProductos = $productos->sum(function($producto) {
            return $producto->stock * $producto->precioVenta;
        });

        $valorTotal = $valorInsumos + $valorProductos;

        // Detalle por categoría de insumos
        $valorPorCategoriaInsumo = DB::table('insumo')
            ->join('categoria_insumo', 'insumo.idCategoria', '=', 'categoria_insumo.idCategoria')
            ->select(
                'categoria_insumo.nombreCategoria',
                DB::raw('SUM(insumo.stock * insumo.precioInsumo) as valor_total')
            )
            ->groupBy('categoria_insumo.nombreCategoria')
            ->get();

        // Detalle por categoría de productos
        $valorPorCategoriaProducto = DB::table('producto')
            ->join('categoria_producto', 'producto.idCategoriaProducto', '=', 'categoria_producto.idCategoriaProducto')
            ->select(
                'categoria_producto.nombreCategoria',
                DB::raw('SUM(producto.stock * producto.precioVenta) as valor_total')
            )
            ->groupBy('categoria_producto.nombreCategoria')
            ->get();

        return response()->json([
            'success' => true,
            'resumen' => [
                'valor_total_inventario' => $valorTotal,
                'valor_insumos' => $valorInsumos,
                'valor_productos' => $valorProductos
            ],
            'detalle_insumos' => $valorPorCategoriaInsumo,
            'detalle_productos' => $valorPorCategoriaProducto
        ]);
    }

    /**
     * Alertas de inventario
     */
    public function alertasInventario()
    {
        $alertas = [
            'criticas' => [],
            'importantes' => [],
            'informativas' => []
        ];

        // Alertas críticas: stock agotado o vencido
        $insumosAgotados = Insumo::where('stock', '<=', 0)->get();
        foreach ($insumosAgotados as $insumo) {
            $alertas['criticas'][] = [
                'tipo' => 'INSUMO_AGOTADO',
                'mensaje' => "Insumo '{$insumo->nombreInsumo}' sin stock",
                'datos' => $insumo
            ];
        }

        $insumosVencidos = Insumo::where('fechaVencimiento', '<', now())->get();
        foreach ($insumosVencidos as $insumo) {
            $alertas['criticas'][] = [
                'tipo' => 'INSUMO_VENCIDO',
                'mensaje' => "Insumo '{$insumo->nombreInsumo}' vencido",
                'datos' => $insumo
            ];
        }

        // Alertas importantes: stock bajo o próximo a vencer
        $insumosStockBajo = Insumo::where('stock', '>', 0)->where('stock', '<', 5)->get();
        foreach ($insumosStockBajo as $insumo) {
            $alertas['importantes'][] = [
                'tipo' => 'STOCK_BAJO',
                'mensaje' => "Insumo '{$insumo->nombreInsumo}' con stock bajo ({$insumo->stock})",
                'datos' => $insumo
            ];
        }

        $insumosProximosVencer = Insumo::whereBetween('fechaVencimiento', [now(), now()->addDays(7)])->get();
        foreach ($insumosProximosVencer as $insumo) {
            $alertas['importantes'][] = [
                'tipo' => 'PROXIMO_VENCER',
                'mensaje' => "Insumo '{$insumo->nombreInsumo}' próximo a vencer ({$insumo->fechaVencimiento->format('d/m/Y')})",
                'datos' => $insumo
            ];
        }

        // Alertas informativas: stock moderado
        $productosStockModerado = Producto::where('stock', '>=', 10)->where('stock', '<', 20)->get();
        foreach ($productosStockModerado as $producto) {
            $alertas['informativas'][] = [
                'tipo' => 'STOCK_MODERADO',
                'mensaje' => "Producto '{$producto->nombre}' con stock moderado ({$producto->stock})",
                'datos' => $producto
            ];
        }

        $totalAlertas = count($alertas['criticas']) + count($alertas['importantes']) + count($alertas['informativas']);

        return response()->json([
            'success' => true,
            'total_alertas' => $totalAlertas,
            'resumen' => [
                'criticas' => count($alertas['criticas']),
                'importantes' => count($alertas['importantes']),
                'informativas' => count($alertas['informativas'])
            ],
            'alertas' => $alertas
        ]);
    }

    /**
     * Historial de stock de un insumo
     */
    public function historialStockInsumo($idInsumo)
    {
        $insumo = Insumo::with('categoria')->findOrFail($idInsumo);

        // Entradas (órdenes de abastecimiento)
        $entradas = AddInsumo::with('orden')
            ->where('idInsumo', $idInsumo)
            ->orderBy('idOrden', 'desc')
            ->limit(20)
            ->get();

        // Salidas (consumo en preparaciones)
        $salidas = DetallePreparacion::with('preparacion')
            ->where('idInsumo', $idInsumo)
            ->orderBy('idPreparacion', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'insumo' => $insumo,
            'stock_actual' => $insumo->stock,
            'entradas' => $entradas,
            'salidas' => $salidas
        ]);
    }
}
