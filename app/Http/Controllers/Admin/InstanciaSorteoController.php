<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstanciaSorteo;
use App\Models\TipoSorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstanciaSorteoController extends Controller
{
    /**
     * Mostrar listado de instancias
     */
    public function index()
    {
        $instancias = InstanciaSorteo::with('tipo')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('admin.instancias_sorteo.index', compact('instancias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $tipos = TipoSorteo::where('activo', 1)->get();
        return view('admin.instancias_sorteo.create', compact('tipos'));
    }

    /**
     * Guardar nueva instancia
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tipo_sorteo_id' => 'required|exists:tipos_sorteo,id',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_cierre' => 'required|date|after:fecha_inicio',
            'loteria_referencia' => 'nullable|string|max:255',
            'estado' => 'required|in:abierta,cerrada,pendiente',
        ]);
        
        try {
            $instancia = InstanciaSorteo::create($validatedData);
            
            // Crear tickets automáticamente según max_tickets del tipo
            $this->generarTickets($instancia);
            
            return redirect()->route('instancias-sorteo.index')
                            ->with('status', 'Instancia de sorteo creada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear instancia de sorteo: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear instancia: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de instancia
     */
    public function show(InstanciaSorteo $instancias_sorteo)
    {
        return view('admin.instancias_sorteo.show', ['instancia' => $instancias_sorteo]);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(InstanciaSorteo $instancias_sorteo)
    {
        $tipos = TipoSorteo::where('activo', 1)->get();
        return view('admin.instancias_sorteo.edit', [
            'instancia' => $instancias_sorteo,
            'tipos' => $tipos
        ]);
    }

    /**
     * Actualizar instancia
     */
    public function update(Request $request, InstanciaSorteo $instancias_sorteo)
    {
        $validatedData = $request->validate([
            'tipo_sorteo_id' => 'required|exists:tipos_sorteo,id',
            'fecha_inicio' => 'required|date',
            'fecha_cierre' => 'required|date|after:fecha_inicio',
            'loteria_referencia' => 'nullable|string|max:255',
            'estado' => 'required|in:abierta,cerrada,pendiente',
        ]);
        
        try {
            $instancias_sorteo->update($validatedData);
            
            return redirect()->route('instancias-sorteo.index')
                            ->with('status', 'Instancia de sorteo actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar instancia de sorteo: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al actualizar instancia: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar instancia
     */
public function destroy($id)
{
    try {
        // Buscar la instancia por ID
        $instancia = InstanciaSorteo::findOrFail($id);
        
        // Verificar si tiene tickets vendidos
        $ticketsVendidos = $instancia->tickets()
                            ->where('estado', 'vendido')
                            ->count();
        
        if ($ticketsVendidos > 0 && !request()->has('force_delete')) {
            return back()->with('warning', 
                "No se puede eliminar la instancia porque tiene {$ticketsVendidos} tickets vendidos. 
                <form action='" . route('instancias-sorteo.destroy', $instancia->id) . "' method='POST' class='d-inline'>
                    " . csrf_field() . "
                    " . method_field('DELETE') . "
                    <input type='hidden' name='force_delete' value='1'>
                    <button type='submit' class='btn btn-sm btn-danger'>Eliminar instancia y todos sus tickets</button>
                </form>");
        }
        
        // Eliminar tickets asociados primero (relación en cascada)
        $instancia->tickets()->delete();
        
        // Eliminar la instancia
        $instancia->delete();
        
        return redirect()->route('instancias-sorteo.index')
                        ->with('status', 'Instancia de sorteo eliminada exitosamente.');
        
    } catch (\Exception $e) {
        \Log::error('Error al eliminar instancia de sorteo: ' . $e->getMessage());
        return back()->with('error', 'Error al eliminar instancia: ' . $e->getMessage());
    }
}
    
    /**
     * Generar tickets para la instancia
     */
    private function generarTickets(InstanciaSorteo $instancia)
    {
        // Esta función se implementará en una fase posterior
        // para crear automáticamente tickets disponibles
        // según la configuración del tipo de sorteo
    }
}