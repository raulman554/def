<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Ticket;
use App\Models\Usuario;
use App\Models\Retiro;
use App\Models\LogTransaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /* SECCIÓN USUARIO */
    
    // 1. Mostrar formulario de solicitud de retiro
    public function showRetiroForm()
    {
        $usuario = Auth::guard('usuario')->user();
        
        if ($usuario->saldo_usd <= 0) {
            return back()->with('error', 'No tienes saldo disponible para retirar.');
        }
        
        return view('usuario.retiro', compact('usuario'));
    }
    
    // 2. Procesar solicitud de retiro
    public function solicitarRetiro(Request $request)
    {
        $usuario = Auth::guard('usuario')->user();
        
        $request->validate([
            'monto_usd' => 'required|numeric|min:10|max:' . $usuario->saldo_usd,
            'medio_pago' => 'required|string|in:binance,banco,oxxo',
            'detalles_pago' => 'required|string|max:255',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Crear solicitud de retiro
            $retiro = new Retiro([
                'usuario_id' => $usuario->id,
                'monto_usd' => $request->monto_usd,
                'estado' => 'pendiente',
                'medio_pago' => $request->medio_pago,
                'comentarios' => $request->detalles_pago,
            ]);
            $retiro->save();
            
            // Actualizar saldo del usuario
            $saldoAnterior = $usuario->saldo_usd;
            $nuevoSaldo = $saldoAnterior - $request->monto_usd;
            $usuario->update(['saldo_usd' => $nuevoSaldo]);
            
            // Registrar la transacción en el log
            LogTransaccion::create([
                'usuario_id' => $usuario->id,
                'tipo' => 'retiro',
                'monto_usd' => $request->monto_usd * -1, // Negativo porque sale dinero
                'descripcion' => 'Solicitud de retiro creada',
            ]);
            
            DB::commit();
            
            return redirect()->route('mi-perfil')
                            ->with('status', 'Solicitud de retiro creada correctamente. El proceso puede tomar hasta 72 horas hábiles.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }
    
    /* SECCIÓN ADMINISTRADOR */
    
    // 3. Listar tickets pendientes de verificación
    public function ticketsPendientes()
    {
        $comprobantes = Comprobante::with(['usuario', 'tickets.instancia.tipo'])
                                  ->where('estado', 'Pendiente')
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(10);
                                  
        return view('admin.pagos.pendientes', compact('comprobantes'));
    }
    
    // 4. Aprobar comprobante
    public function aprobar(Request $request, $id)
    {
        $comprobante = Comprobante::with('tickets', 'usuario')->findOrFail($id);
        $admin = Auth::guard('administrador')->user();
        
        if ($comprobante->estado !== 'Pendiente') {
            return back()->with('error', 'Este comprobante ya ha sido procesado.');
        }
        
        try {
            DB::beginTransaction();
            
            // Actualizar el comprobante
            $comprobante->update([
                'estado' => 'Aprobado',
                'id_admin_verifico' => $admin->id,
                'fecha_verificacion' => now(),
            ]);
            
            // Actualizar los tickets
            foreach ($comprobante->tickets as $ticket) {
                $ticket->update([
                    'estado' => 'vendido',
                ]);
            }
            
            // No acreditamos saldo al usuario porque los tickets ya se pagaron
            
            DB::commit();
            
            return redirect()->route('admin.tickets.pendientes')
                            ->with('status', 'Comprobante aprobado correctamente.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar el comprobante: ' . $e->getMessage());
        }
    }
    
    // 5. Rechazar comprobante
    public function rechazar(Request $request, $id)
    {
        $comprobante = Comprobante::with('tickets', 'usuario')->findOrFail($id);
        $admin = Auth::guard('administrador')->user();
        
        $request->validate([
            'motivo_rechazo' => 'required|string|max:255',
        ]);
        
        if ($comprobante->estado !== 'Pendiente') {
            return back()->with('error', 'Este comprobante ya ha sido procesado.');
        }
        
        try {
            DB::beginTransaction();
            
            // Actualizar el comprobante
            $comprobante->update([
                'estado' => 'Rechazado',
                'id_admin_verifico' => $admin->id,
                'fecha_verificacion' => now(),
            ]);
            
            // Liberar los tickets (vuelven a estar disponibles)
            foreach ($comprobante->tickets as $ticket) {
                $ticket->update([
                    'estado' => 'disponible',
                    'usuario_id' => null,
                    'id_comprobante' => null,
                    'reservado_hasta' => null,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.tickets.pendientes')
                            ->with('status', 'Comprobante rechazado y tickets liberados correctamente.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar el comprobante: ' . $e->getMessage());
        }
    }
}