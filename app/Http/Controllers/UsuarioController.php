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
            // Agrega más campos aquí si los necesitas
        ]);

        $usuario->update($data + ['perfil_completo' => true]);

        return redirect()->route('mi-perfil')
                         ->with('status', 'Perfil actualizado correctamente.');
    }
}

