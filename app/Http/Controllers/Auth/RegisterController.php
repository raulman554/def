<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /** Mostrar formulario */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /** Procesar registro */
    public function register(Request $request)
    {
        // Validación
        $data = $request->validate([
            'nombre'            => ['required','string','max:100'],
            'apellido'          => ['required','string','max:100'],
            'email'             => ['required','email','max:255','unique:usuarios,email'],
            'password'          => ['required','string','min:6','confirmed'],
            'fecha_nacimiento'  => ['required','date'],
        ]);

        // Verificar mayoría de edad (≥ 18)
        $edad = Carbon::parse($data['fecha_nacimiento'])->age;
        if ($edad < 18) {
            return back()
                ->withErrors(['fecha_nacimiento' => 'Debes ser mayor de 18 años para registrarte.'])
                ->withInput();
        }

        // Crear usuario
        $usuario = Usuario::create([
            'nombre'           => $data['nombre'],
            'apellido'         => $data['apellido'],
            'email'            => $data['email'],
            'password'         => Hash::make($data['password']),
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'saldo_usd'        => 0,
        ]);

        // Autenticar
        Auth::guard('usuario')->login($usuario);

        return redirect()->route('mi-perfil')
                         ->with('status','¡Registro exitoso!');
    }
}
