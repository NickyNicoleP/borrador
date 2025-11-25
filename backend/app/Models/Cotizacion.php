<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    public $timestamps = false;

    protected $fillable = [
        'tipo_servicio',
        'datos_gb',
        'minutos',
        'sms',
        'precio_final',
        'plan_recomendado',
        'email',
        'telefono'
    ];
}


