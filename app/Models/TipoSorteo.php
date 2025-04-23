<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSorteo extends Model
{
    protected $table = 'tipos_sorteo';

    protected $fillable = [
        'nombre',
        'precio_ticket_usd',
        'max_tickets',
        'payout_ratio',
        'frecuencia_desc',
        'descripcion_breve',
        'activo',
    ];

    // Relación con niveles
    public function niveles()
    {
        return $this->hasMany(NivelPremio::class,'tipo_sorteo_id','id')
                    ->orderBy('nivel','asc');
    }
}


