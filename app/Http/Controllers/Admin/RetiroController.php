<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Retiro;
use App\Models\LogTransaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RetiroController extends Controller
{
    public function index()
    {
        $retiros = Retiro::with('usuario')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
        return view('admin.retiros.index', compact('retiros'));
    }

    public function update(Request $request, $id)
    {
        $retiro = Retiro::findOrFail($id);
        $admin = Auth::guard('administrador')->user();
        
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
            'notas_admin' => 'nullable|string|max:255',
        ]);
        
        $accion = $request->input('accion');
        $notas = $request->input('notas_admin');
        
        // Solo procesar si está pendiente
        if ($retiro->estado !== 'pendiente') {
            return back()->with('error', 'Este retiro ya ha sido procesado anteriormente.');
        }
        
        try {
            DB::beginTransaction();
            
            if ($accion === 'aprobar') {
                $retiro->update([
                    'estado' => 'completado',
                    'comentarios' => $notas,
                    'id_admin_proceso' => $admin->id,
                    'fecha_proceso' => now(),
                ]);
                
                // Registrar la transacción en el log
                LogTransaccion::create([
                    'usuario_id' => $retiro->usuario_id,
                    'tipo' => 'retiro',
                    'monto_usd' => $retiro->monto_usd * -1, // Negativo porque sale dinero
                    'descripcion' => 'Retiro completado: ' . $retiro->medio_pago,
                ]);
                
                $mensaje = 'Retiro aprobado y completado correctamente.';
            } else {
                // Devolver el saldo al usuario
                $usuario = $retiro->usuario;
                $saldoAnterior = $usuario->saldo_usd;
                $nuevoSaldo = $saldoAnterior + $retiro->monto_usd;
                
                $usuario->update(['saldo_usd' => $nuevoSaldo]);
                
                $retiro->update([
                    'estado' => 'rechazado',
                    'comentarios' => $notas,
                    'id_admin_proceso' => $admin->id,
                    'fecha_proceso' => now(),
                ]);
                
                // Registrar la transacción de devolución
                LogTransaccion::create([
                    'usuario_id' => $retiro->usuario_id,
                    'tipo' => 'deposito', // Es un depósito porque entra dinero
                    'monto_usd' => $retiro->monto_usd,
                    'descripcion' => 'Devolución por rechazo de retiro #' . $retiro->id,
                ]);
                
                $mensaje = 'Retiro rechazado y saldo reintegrado al usuario.';
            }
            
            DB::commit();
            return redirect()->route('retiros.index')->with('status', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar el retiro: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        return back()->with('error', 'No se pueden eliminar solicitudes de retiro.');
    }
}