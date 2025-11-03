<?php

namespace App\Livewire\Almacen;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class GestionPagos extends Component
{
    public $pagosPendientes = [];
    public $showForm = false;
    public $idPago = '';
    public $idTipoPago = '';
    public $fechaPago = '';
    public $observaciones = '';

    public function mount()
    {
        $this->loadPagosPendientes();
    }

    public function loadPagosPendientes()
    {
        try {
            $this->pagosPendientes = DB::table('pago_orden')
                ->where('pago_orden.estado', 1)
                ->leftJoin('orden_abastecimiento', 'pago_orden.idPagoOrden', '=', 'orden_abastecimiento.idPagoOrden')
                ->leftJoin('proveedor', 'orden_abastecimiento.idProveedor', '=', 'proveedor.idProveedor')
                ->select(
                    'pago_orden.idPagoOrden',
                    'pago_orden.montoTotal',
                    'pago_orden.created_at as fechaCreacion',
                    'orden_abastecimiento.idOrden',
                    'proveedor.razonSocial'
                )
                ->orderBy('pago_orden.created_at', 'DESC')
                ->get()
                ->toArray();

            \Log::info('Pagos pendientes cargados: ' . count($this->pagosPendientes));
        } catch (\Exception $e) {
            \Log::error('Error cargando pagos: ' . $e->getMessage());
            session()->flash('error', 'Error cargando pagos: ' . $e->getMessage());
            $this->pagosPendientes = [];
        }
    }

    public function abrirFormulario($idPago)
    {
        $this->idPago = $idPago;
        $this->idTipoPago = '1'; // Por defecto: Efectivo
        $this->fechaPago = now()->format('Y-m-d');
        $this->observaciones = '';
        $this->showForm = true;
    }

    public function registrarPago()
    {
        if (empty($this->idPago) || empty($this->idTipoPago)) {
            session()->flash('error', '⚠️ Completa todos los campos requeridos');
            return;
        }

        try {
            DB::beginTransaction();

            // Usar where en lugar de find, ya que la PK es idPagoOrden, no id
            $pagoOrden = DB::table('pago_orden')
                ->where('idPagoOrden', (int)$this->idPago)
                ->first();
            
            if (!$pagoOrden) {
                session()->flash('error', '❌ Pago no encontrado');
                DB::rollBack();
                return;
            }

            // Actualizar estado del pago pendiente a pagado
            DB::table('pago_orden')
                ->where('idPagoOrden', (int)$this->idPago)
                ->update([
                    'estado' => 2, // 2 = pagado
                    'idTipoPago' => (int)$this->idTipoPago,
                    'updated_at' => now()
                ]);

            // Obtener el próximo ID para pago
            $nextPagoId = (DB::table('pago')->max('idPago') ?? 0) + 1;

            // Registrar en la tabla pago - usando solo los campos que la tabla tiene
            try {
                DB::table('pago')->insert([
                    'idPago' => $nextPagoId,
                    'idPagoOrden' => (int)$this->idPago,
                    'nroOperacion' => null,
                    'fechaPago' => $this->fechaPago
                ]);
            } catch (\Exception $insertError) {
                // Si falla la inserción, intentar con menos campos
                \Log::warning('Insert con todos los campos falló: ' . $insertError->getMessage());
                DB::table('pago')->insert([
                    'idPago' => $nextPagoId,
                    'idPagoOrden' => (int)$this->idPago
                ]);
            }

            DB::commit();

            session()->flash('success', '✅ Pago registrado correctamente. Factura #' . $this->idPago . ' marcada como pagada.');
            $this->showForm = false;
            $this->resetForm();
            $this->loadPagosPendientes();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error registrando pago: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            session()->flash('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function cancelar()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->idPago = '';
        $this->idTipoPago = '';
        $this->fechaPago = '';
        $this->observaciones = '';
    }

    public function render()
    {
        return view('livewire.almacen.gestion-pagos');
    }
}

