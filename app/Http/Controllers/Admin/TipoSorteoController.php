<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoSorteo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TipoSorteoController extends Controller
{
    /**
     * Listar tipos de sorteo
     */
    public function index()
    {
        $tipos = TipoSorteo::orderBy('id', 'desc')->paginate(10);
        return view('admin.tipos_sorteo.index', compact('tipos'));
    }

    /**
     * Mostrar formulario de creaci¨®n
     */
    public function create()
    {
        return view('admin.tipos_sorteo.create');
    }

    /**
     * Guardar nuevo tipo de sorteo
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        
        // Si el payout viene como porcentaje (0-100), convertirlo a decimal (0-1)
        if ($data['payout_ratio'] > 1) {
            $data['payout_ratio'] = $data['payout_ratio'] / 100;
        }
        
        // Manejar checkbox de activo
        $data['activo'] = $request->has('activo') ? 1 : 0;
        
        TipoSorteo::create($data);
        
        return redirect()->route('tipos-sorteo.index')
                         ->with('status', 'Tipo de sorteo creado correctamente.');
    }

    /**
     * Mostrar formulario de edici¨®n
     */
    public function edit(TipoSorteo $tipos_sorteo)
    {
        return view('admin.tipos_sorteo.edit', ['tipo' => $tipos_sorteo]);
    }

    /**
     * Actualizar tipo de sorteo
     */
    public function update(Request $request, TipoSorteo $tipos_sorteo)
    {
        $data = $this->validateData($request);
        
        // Si el payout viene como porcentaje (0-100), convertirlo a decimal (0-1)
        if ($data['payout_ratio'] > 1) {
            $data['payout_ratio'] = $data['payout_ratio'] / 100;
        }
        
        // Manejar checkbox de activo
        $data['activo'] = $request->has('activo') ? 1 : 0;
        
        $tipos_sorteo->update($data);
        
        return redirect()->route('tipos-sorteo.index')
                         ->with('status', 'Tipo de sorteo actualizado correctamente.');
    }

    /**
     * Eliminar un tipo de sorteo
     */
    public function destroy(TipoSorteo $tipos_sorteo)
    {
        try {
            // Verificar si tiene premios asociados
            $premiosCount = $tipos_sorteo->niveles()->count();
            
            // Si tiene premios y no se ha confirmado la eliminaci¨®n
            if ($premiosCount > 0 && !request()->has('force_delete')) {
                return back()->with('warning', 
                    "No se puede eliminar el sorteo porque tiene {$premiosCount} premios asociados. 
                    <form action='" . route('tipos-sorteo.destroy', $tipos_sorteo->id) . "' method='POST' class='d-inline'>
                        " . csrf_field() . "
                        " . method_field('DELETE') . "
                        <input type='hidden' name='force_delete' value='1'>
                        <button type='submit' class='btn btn-sm btn-danger'>Eliminar sorteo y todos sus premios</button>
                    </form>");
            }
            
            // Eliminar el tipo de sorteo (y en cascada sus premios gracias a la FK)
            $tipos_sorteo->delete();
            return back()->with('status', 'Tipo de sorteo eliminado correctamente.');
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar tipo de sorteo: ' . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el tipo de sorteo. Error: ' . $e->getMessage());
        }
    }

    /**
     * Validar datos del formulario
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'precio_ticket_usd' => 'required|numeric|min:0.01',
            'max_tickets' => 'required|integer|min:1',
            'payout_ratio' => 'required|numeric|min:0|max:100',
            'frecuencia_desc' => 'required|string|max:100',
            'descripcion_breve' => 'nullable|string',
        ]);
    }
}