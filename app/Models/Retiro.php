<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retiro extends Model
{
    protected $table = 'retiros';

    protected $fillable = [
        'usuario_id',
        'monto_usd',
        'estado',
        'medio_pago',
        'comentarios',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
