<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelPremio extends Model
{
    protected $table = 'niveles_premio';

    protected $fillable = [
        'tipo_sorteo_id',
        'nivel',
        'cantidad_ganadores',
        'monto_premio_usd',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoSorteo::class,'tipo_sorteo_id','id');
    }
}
