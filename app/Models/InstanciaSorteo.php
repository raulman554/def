<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstanciaSorteo extends Model
{
    // Especifica la tabla asociada
    protected $table = 'instancias_sorteo';

    // Define los campos que pueden ser asignados masivamente
    protected $fillable = [
        'tipo_sorteo_id',
        'fecha_inicio',
        'fecha_cierre',
        'loteria_referencia',
        'estado',
    ];

    // Relaci¨®n con el modelo TipoSorteo
    public function tipo()
    {
        return $this->belongsTo(TipoSorteo::class, 'tipo_sorteo_id', 'id');
    }

    // M¨¦todo para la relaci¨®n con Tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'instancia_sorteo_id');
    }
}