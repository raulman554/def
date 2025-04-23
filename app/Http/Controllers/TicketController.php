<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstanciaSorteo;
use App\Models\Ticket;
use App\Models\Comprobante;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketController extends Controller
{
    // 1) Listar todas las instancias abiertas
    public function index()
    {
        $hoy = now();
        $sorteos = InstanciaSorteo::where('estado', 'abierta')
                     ->where('fecha_inicio', '<=', $hoy)
                     ->where('fecha_cierre', '>=', $hoy)
                     ->orderBy('fecha_cierre')
                     ->get();

        return view('sorteos.index', compact('sorteos'));
    }

    // 2) Mostrar detalle de una instancia
    public function show($id)
    {
        $sorteo = InstanciaSorteo::with('tipo')->findOrFail($id);
        
        // Obtener números ya reservados/vendidos
        $numerosReservados = Ticket::where('instancia_sorteo_id', $id)
                                  ->where(function($query) {
                                      $query->where('estado', 'reservado')
                                           ->orWhere('estado', 'vendido');
                                  })
                                  ->pluck('numero')
                                  ->toArray();
        
        return view('sorteos.show', compact('sorteo', 'numerosReservados'));
    }

    // 3) Reservar números
    public function reservar(Request $request, $id)
    {
        $sorteo = InstanciaSorteo::findOrFail($id);
        $usuario = Auth::guard('usuario')->user();
        
        // Verificar si el perfil está completo
        if (!$usuario->perfil_completo) {
            return redirect()->route('mi-perfil.editar')
                            ->with('error', 'Debes completar tu perfil antes de participar en sorteos.');
        }
        
        // Validar array de números
        $data = $request->validate([
            'numeros' => ['required', 'array', 'min:1'],
            'numeros.*' => ['required', 'string', 'max:10'],
        ]);
        
        // Verificar que los números no estén ya reservados
        $numerosOcupados = Ticket::where('instancia_sorteo_id', $id)
                               ->whereIn('numero', $data['numeros'])
                               ->where(function($query) {
                                   $query->where('estado', 'reservado')
                                        ->orWhere('estado', 'vendido');
                               })
                               ->exists();
                               
        if ($numerosOcupados) {
            return back()->with('error', 'Algunos números ya han sido reservados. Por favor, actualiza la página e intenta de nuevo.');
        }
        
        // Definir tiempo de expiración (15 minutos)
        $expiracion = Carbon::now()->addMinutes(15);
        
        // Crear un ticket por cada número seleccionado
        foreach ($data['numeros'] as $numero) {
            Ticket::create([
                'instancia_sorteo_id' => $sorteo->id,
                'usuario_id' => $usuario->id,
                'numero' => $numero,
                'estado' => 'reservado',
                'reservado_hasta' => $expiracion,
                'precio_usd' => $sorteo->tipo->precio_ticket_usd,
            ]);
        }
        
        // Calcular total a pagar
        $totalPagar = count($data['numeros']) * $sorteo->tipo->precio_ticket_usd;

        return redirect()->route('sorteos.pago', $id)
                         ->with('status', 'Números reservados correctamente. Tienes 15 minutos para completar el pago.')
                         ->with('numeros', $data['numeros'])
                         ->with('total', $totalPagar);
    }

    // 4) Mostrar página de pago
    public function pago($id)
    {
        if (!session('numeros') || !session('total')) {
            return redirect()->route('sorteos.show', $id);
        }
        
        $sorteo = InstanciaSorteo::with('tipo')->findOrFail($id);
        $numeros = session('numeros');
        $total = session('total');
        
        return view('sorteos.pago', compact('sorteo', 'numeros', 'total'));
    }

    // 5) Subir comprobante de pago
    public function subirComprobante(Request $request, $id)
    {
        $sorteo = InstanciaSorteo::findOrFail($id);
        $usuario = Auth::guard('usuario')->user();
        
        $request->validate([
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
            'numeros' => 'required|array',
            'total' => 'required|numeric',
        ]);
        
        // Verificar que los tickets siguen reservados
        $tickets = Ticket::where('instancia_sorteo_id', $id)
                        ->where('usuario_id', $usuario->id)
                        ->where('estado', 'reservado')
                        ->whereIn('numero', $request->numeros)
                        ->get();
        
        if (count($tickets) != count($request->numeros)) {
            return back()->with('error', 'Algunos tickets ya no están disponibles o han expirado.');
        }
        
        // Guardar el comprobante
        $path = $request->file('comprobante')->store('comprobantes', 'public');
        
        // Crear registro del comprobante
        $comprobante = new Comprobante([
            'usuario_id' => $usuario->id,
            'monto_esperado_usd' => $request->total,
            'ruta_archivo' => $path,
            'estado' => 'Pendiente',
        ]);
        $comprobante->save();
        
        // Actualizar el estado de los tickets
        foreach ($tickets as $ticket) {
            $ticket->update([
                'estado' => 'Pendiente_Verificacion',
                'id_comprobante' => $comprobante->id,
            ]);
        }
        
        return redirect()->route('mis-tickets')
                         ->with('status', 'Comprobante subido correctamente. El pago está siendo verificado.');
    }

    // 6) Ver mis tickets/reservas
    public function misTickets()
    {
        $usuario = Auth::guard('usuario')->user();
        $tickets = Ticket::with('instancia.tipo')
                    ->where('usuario_id', $usuario->id)
                    ->orderByDesc('created_at')
                    ->get();

        // Agrupar tickets por instancia y estado
        $ticketsAgrupados = $tickets->groupBy(function($ticket) {
            return $ticket->instancia_sorteo_id . '_' . $ticket->estado;
        });

        return view('sorteos.mis_tickets', compact('ticketsAgrupados'));
    }
}