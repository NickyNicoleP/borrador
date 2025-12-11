<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'tipo_servicio',
        'datos_gb',
        'minutos',
        'sms',
        'precio_mensual',
        'descripcion',
        'activo',
    ];
}
