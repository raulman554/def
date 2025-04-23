<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /** Mostrar perfil */
    public function show()
    {
        $usuario = Auth::guard('usuario')->user();
        return view('usuario.show', compact('usuario'));
    }

    /** Formulario para completar / editar perfil */
    public function edit()
    {
        $usuario = Auth::guard('usuario')->user();
        return view('usuario.edit', compact('usuario'));
    }

    /** Guardar cambios de perfil */
public function update(Request $request)
{
    $usuario = Auth::guard('usuario')->user();

    $data = $request->validate([
        'apellido'         => 'required|string|max:100',
        'numero_whatsapp'  => 'nullable|string|max:30',
        'fecha_nacimiento' => 'nullable|date',
        'id_binance'       => 'nullable|string|max:255',
    ]);

    // Verificación detallada de cada campo
    $perfil_completo = true;

    // Verificar cada campo individualmente
    if (empty($data['apellido'])) $perfil_completo = false;
    if (empty($data['numero_whatsapp'])) $perfil_completo = false;
    if (empty($data['fecha_nacimiento'])) $perfil_completo = false;
    if (empty($data['id_binance'])) $perfil_completo = false;

    $usuario->update(array_merge($data, ['perfil_completo' => $perfil_completo]));

    return redirect()->route('mi-perfil')
                     ->with('status', 'Perfil actualizado correctamente.');
}
}

