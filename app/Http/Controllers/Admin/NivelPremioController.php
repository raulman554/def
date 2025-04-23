<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoSorteo;
use App\Models\NivelPremio;
use Illuminate\Http\Request;

class NivelPremioController extends Controller
{

    /* Lista de niveles para un tipo */
    public function index(TipoSorteo $tipo)
    {
        // Ordenamos por id en lugar de 'nivel'
        $niveles = $tipo->niveles()->orderBy('id')->get();
        return view('admin.niveles_premio.index', compact('tipo', 'niveles'));
    }


    /* Form crear */
    public function create(TipoSorteo $tipo)
    {
        return view('admin.niveles_premio.create', compact('tipo'));
    }

    /* Guardar nuevo */
    public function store(Request $request, TipoSorteo $tipo)
    {
        $data = $this->validateData($request, $tipo->id);
        $tipo->niveles()->create($data);
        return redirect()->route('tipos-sorteo.niveles-premio.index', $tipo)
                         ->with('status', 'Premio creado.');
    }

    /* Form editar */
    public function edit(TipoSorteo $tipo, NivelPremio $niveles_premio)
    {
        return view('admin.niveles_premio.edit', [
            'tipo'   => $tipo,
            'nivel'  => $niveles_premio,
        ]);
    }

    /* Actualizar */
    public function update(Request $request, TipoSorteo $tipo, NivelPremio $niveles_premio)
    {
        $data = $this->validateData($request, $tipo->id, $niveles_premio->id);
        $niveles_premio->update($data);

        return redirect()->route('tipos-sorteo.niveles-premio.index', $tipo)
                         ->with('status', 'Premio actualizado.');
    }

    /* Eliminar */
    public function destroy(TipoSorteo $tipo, NivelPremio $niveles_premio)
    {
        $niveles_premio->delete();
        return redirect()->route('tipos-sorteo.niveles-premio.index', $tipo)
                         ->with('status', 'Premio eliminado.');
    }

    /* ---------- Validaci¨®n ---------- */
    private function validateData(Request $r, int $tipoId, int $nivelId = null): array
    {
        return $r->validate([
            'nivel' => 'required|integer|min:1|unique:niveles_premio,nivel,' 
                     . $nivelId . ',id,tipo_sorteo_id,' . $tipoId,
            'cantidad_ganadores' => 'required|integer|min:1',
            'monto_premio_usd'   => 'required|numeric|min:0.01',
        ]);
    }
}