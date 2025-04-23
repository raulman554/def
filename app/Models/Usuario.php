<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
    'nombre',
    'apellido',          // NUEVO
    'email',
    'password',
    'fecha_nacimiento',  // NUEVO
    'numero_whatsapp',
    'saldo_usd',
    'perfil_completo',
    'id_binance',
    'detalles_cuenta_bancaria',
    'datos_pago_oxxo',
    'ultima_conexion',
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function retiros()
    {
        return $this->hasMany(Retiro::class);
    }

    public function transacciones()
    {
        return $this->hasMany(LogTransaccion::class);
    }
}
