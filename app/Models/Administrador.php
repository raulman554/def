<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable
{
    protected $table = 'administradores';

    /* ← Asegúrate de que ESTOS campos estén en $fillable */
    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];
}
