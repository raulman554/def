<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'instancia_sorteo_id',
        'usuario_id',
        'numero',
        'estado',
        'reservado_hasta',
        'precio_usd',
    ];

    public function instancia()
    {
        return $this->belongsTo(InstanciaSorteo::class, 'instancia_sorteo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function comprobante()
    {
        return $this->hasOne(Comprobante::class, 'ticket_id');
    }
}

