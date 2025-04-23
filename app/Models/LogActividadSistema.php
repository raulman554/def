<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActividadSistema extends Model
{
    protected $table = 'log_actividad_sistema';

    protected $fillable = [
        'usuario_tipo',
        'usuario_id',
        'accion',
        'detalles',
    ];
}
