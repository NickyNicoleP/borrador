<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudPortabilidad extends Model
{
    protected $table = 'solicitudes_portabilidad';

    protected $fillable = [
        'nombre_cliente',
        'email',
        'telefono',
        'compania_origen',
        'numero_a_portar',
        'plan_id',
        'estado',
        'notas',
    ];
}
