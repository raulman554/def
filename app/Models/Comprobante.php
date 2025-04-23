<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 'comprobantes';

    protected $fillable = [
        'usuario_id',
        'monto_esperado_usd',
        'ruta_archivo',
        'numero_referencia',
        'estado',
        'id_admin_verifico',
        'fecha_verificacion',
    ];

    protected $dates = [
        'fecha_verificacion',
        'created_at',
        'updated_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_comprobante');
    }

    public function admin()
    {
        return $this->belongsTo(Administrador::class, 'id_admin_verifico');
    }
}