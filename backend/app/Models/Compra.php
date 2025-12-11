<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'usuario_id',
        'plan_id',
        'promocion_id',
        'blockchain_transaccion_id',
        'email_contacto',
        'telefono',
        'monto',
        'moneda',
        'estado',
        'notas',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function promocion()
    {
        return $this->belongsTo(Promocion::class);
    }

    public function blockchainTransaccion()
    {
        return $this->belongsTo(BlockchainTransaccion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
