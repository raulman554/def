<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTransaccion extends Model
{
    protected $table = 'log_transacciones';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'monto_usd',
        'descripcion',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
