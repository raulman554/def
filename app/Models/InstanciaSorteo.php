<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstanciaSorteo extends Model
{
    // Especifica la tabla asociada
    protected $table = 'instancias_sorteo';

    // Define la clave primaria
    protected $primaryKey = 'id';

    // Define los campos que pueden ser asignados masivamente
    protected $fillable = [
        'tipo_sorteo_id',
        'fecha_inicio',
        'fecha_cierre',
        'loteria_referencia',
        'estado',
    ];

    // Relación con el modelo TipoSorteo
    public function tipo()
    {
        return $this->belongsTo(TipoSorteo::class, 'tipo_sorteo_id', 'id');
    }
}